$(document).ready(function() {
    var access_rights = $('#access_rights tr');
    var userID = null;

    $('#btnAddUserAccount').click(function() {
        userID = null;
        clearAccountForm();
        $("#UserAccountForm").modal('show');
    })

    /* 
        Load list of user accounts via datatable
    */
    var user_account_list = $('#UserAccountList').DataTable({
        "processing": true,
        "serverSide": true,
        "order": [],
        // "autoWidth": false,
        "ajax": {
            url: base_url + 'User_account/user_account_list',
            type: "post",
        },
        "columnDefs": [{
            "targets": [0, 5],
            "orderable": false,
        }, {
            "width": "3.33%",
            "targets": 0,

        }, {
            "width": "30.33%",
            "targets": 1,

        }, {
            "width": "20.33%",
            "targets": 2,

        }, {
            "width": "15.33%",
            "targets": 4,

        }]

    });

    $('.btnCancelAccForm').click(function(e) {
        e.preventDefault();
        clearAccountForm();
    })

    $('input[name=lname],input[name=fname],input[name=email],input[name=password],input[name=address],select[name=gender],select[name=brgyCode],select[name=citymunCode],select[name=provCode],input[name=contactNo]').focus(removeErrorFields);


    function removeErrorFields() {
        $(this).removeClass('error-field').parent('.form-group').find('.error-msg').html('');
    }

    $('#JobSeekerRegForm').submit(function(e) {
        e.preventDefault();
        var data = $(this).serialize();
        $.ajax({
            url: base_url + 'Jobs/registerJobSeekerAcc',
            method: 'post',
            data: data,
            success: function(result) {
                if (result.success) {

                } else {
                    $.each(result.msg, function(key, value) {
                        console.log(key + ':' + value)
                        if (value != '') {
                            $('input[name=' + key + '],select[name=' + key + ']').addClass('error-field').parent('.form-group').find('.error-msg').html(value);
                        }
                    });

                }
            }
        });
    });


    //PERFORMS ADDING AND UPDATING USER ACCOUNT INFORMATION
    $('.btnSaveAccount').click(function(e) {
        e.preventDefault();
        var _fullname = $("input[name=fullname]").val();
        var _position = $('input[name=position]').val();
        var _uname = $("input[name=uname]").val();
        var _pass = $("input[name=pass]").val();

        var userAccess_rights = new Array();
        for (var i = 0; i < access_rights.length; i++) {
            var data = {};
            if (access_rights.eq(i).find('input[name=input_modules]').prop('checked') == true) {
                data.moduleID = access_rights.eq(i).find('input[name=input_modules]').data('module_id')
                if (access_rights.eq(i).find('input[name=add]').prop('checked') == true) {
                    data._add = true
                } else {
                    data._add = false
                }

                if (access_rights.eq(i).find('input[name=edit]').prop('checked') == true) {
                    data._edit = true

                } else {
                    data._edit = false

                }
                if (access_rights.eq(i).find('input[name=delete]').prop('checked') == true) {
                    data._delete = true
                } else {
                    data._delete = false
                }
                userAccess_rights.push(data);

            }
        }
        _url = (userID == null) ? base_url + 'User_account/addnew_account' : base_url + 'User_account/update_account/' + userID;
        $.ajax({
            url: _url,
            method: 'post',
            data: {
                fullname: _fullname,
                uname: _uname,
                pass: _pass,
                position: _position,
                access_rights: userAccess_rights
            },
            beforeSend: function() {
                $("#loading_screen").addClass('loading').removeClass('remove-loading');
            },
            success: function(result) {
                if (result.status) {
                    user_account_list.ajax.reload();
                    userID = null;
                    $.bootstrapGrowl(result.msg, {
                        type: 'success'
                    });
                    $("#loading_screen").addClass('remove-loading').removeClass('loading');
                    clearAccountForm()
                } else {
                    $.bootstrapGrowl(result.msg, {
                        type: 'danger'
                    });
                }
            },
            complete: function() {
                $("#loading_screen").addClass('remove-loading').removeClass('loading');

            }
        })

    });


    //Selecting all user accounts
    $('input[name=inputSelectAllUserAcc]').change(function() {
        var user_list = $("#UserAccount_list tr");
        if ($(this).prop('checked') == true) {
            for (var i = 0; i < user_list.length; i++) {
                user_list.eq(i).find('input[name=inputSelectUserAccount]').prop('checked', true);
            }
        } else {
            for (var i = 0; i < user_list.length; i++) {
                user_list.eq(i).find('input[name=inputSelectUserAccount]').prop('checked', false);
            }
        }
    })

    //PERFORMS SELECTING SPECIFIC USER ACCOUNT INFO
    $(document).on('click', '.btnEditUser', function() {
        userID = $(this).data('id');
        $.ajax({
            url: base_url + 'User_account/getUserAccount',
            method: 'post',
            data: {
                userID: userID
            },
            beforeSend: function() {
                $("#loading_screen").addClass('loading').removeClass('remove-loading');
            },
            success: function(result) {
                clearAccountForm();
                $('input[name=fullname]').val(result.user_data['fullname']);
                $('input[name=uname]').val(result.user_data['username']);
                $('input[name=position]').val(result.user_data['position']);
                console.log(result);
                $.each(result.access_rights, function(key, value) {
                    console.log('ModulesID: ' + value.moduleID + ' | ' + ' Add: ' + Boolean(Number(value._add)) + ' Edit: ' + Boolean(Number(value._edit)) + ' Delete: ' + Boolean(Number(value._delete)));
                    access_rights.find('input[name=input_modules][data-module_id="' + value.moduleID + '"]').prop('checked', true);

                    for (var i = 0; i < access_rights.length; i++) {
                        if (access_rights.eq(i).find('input[name=input_modules]').data('module_id') == value.moduleID) {
                            access_rights.eq(i).find('input[type=checkbox][name=add]').prop('checked', Boolean(Number(value._add)));
                            access_rights.eq(i).find('input[type=checkbox][name=edit]').prop('checked', Boolean(Number(value._edit)));
                            access_rights.eq(i).find('input[type=checkbox][name=delete]').prop('checked', Boolean(Number(value._delete)));
                        }
                    }


                })
                $("#UserAccountForm").modal('show');
            },
            complete: function() {
                $("#loading_screen").addClass('remove-loading').removeClass('loading');

            }
        })
    });

    //PERFORMS DELETING USER ACCOUNT...
    $(document).on('click', '.btnDeleteUser', function() {
        var id = $(this).data('id');
        if (confirm("Do you want to delete selected user account?")) {
            $.ajax({
                url: base_url + 'User_account/delete_account',
                method: 'post',
                data: {
                    userID: id
                },
                beforeSend: function() {
                    $("#loading_screen").addClass('loading').removeClass('remove-loading');
                },
                success: function(result) {
                    if (result.status) {
                        user_account_list.ajax.reload();
                        $.bootstrapGrowl(result.msg, {
                            type: 'success'
                        });
                        $("#loading_screen").addClass('remove-loading').removeClass('loading');
                    } else {
                        $.bootstrapGrowl(result.msg, {
                            type: 'danger'
                        });
                    }
                },
                complete: function() {
                    $("#loading_screen").addClass('remove-loading').removeClass('loading');

                }
            })
        }
    })


    //PERFORMS ACTIVE INACTIVE USER ACCOUNTS
    $(document).on('click', '.btnSetActiveInactive', function() {
        var active_status = Boolean(Number($(this).data('status')));
        var btn = $(this);

        userID = $(this).data('id');
        $.ajax({
            url: base_url + 'User_account/setActiveInactive',
            method: 'post',
            data: {
                userID: userID,
                status: active_status
            },
            beforeSend: function() {
                $("#loading_screen").addClass('loading').removeClass('remove-loading');
            },
            success: function(result) {
                if (result.status) {
                    // user_account_list.ajax.reload();
                    if (active_status) {
                        console.log('True');
                        btn.removeClass('btn-success').addClass('btn-danger').text('Inactive').data('status', 0);
                    } else {
                        console.log('False');
                        btn.removeClass('btn-danger').addClass('btn-success').text('Active').data('status', 1);
                    }
                    $.bootstrapGrowl(result.msg, {
                        type: 'success'
                    });
                    $("#loading_screen").addClass('remove-loading').removeClass('loading');
                } else {
                    $.bootstrapGrowl(result.msg, {
                        type: 'danger'
                    });
                }
            },
            complete: function() {
                $("#loading_screen").addClass('remove-loading').removeClass('loading');

            }

        })
    });


});

function clearAccountForm() {
    $("#UserAccountForm input[type=text],input[type=password]").val('');
    $("#UserAccountForm input[type=checkbox]").prop('checked', false);

}