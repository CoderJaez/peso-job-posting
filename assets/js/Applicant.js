$(document).ready(function() {
            var applicantID = null;
    $('[data-mask]').inputmask();


            //Performs editing applicant's information
    $('.edit-info').click(function () {

        $.ajax({
            url: base_url + 'resumes/getApplicantData',
            method: 'post',
            beforeSend: function () {
                $('#loading_screen').addClass('loading').removeClass('remove-loading');
            },
            success: function (data) {
                $('input[name=name]').val(data.name);
                $('input[name=bday]').val(data.bday);
                $('input[name=email]').val(data.email);
                $('input[name=contactNo]').val(data.contactNo);
                $('input[name=address]').val(data.address);
                $('select[name=gender]').find('option[value="' + data.gender + '"]').prop('selected', true);
                $('select[name=provCode]').find('option[value="' + data.provCode + '"]').prop('selected', true);
                loadCity(data.provCode, data.citymunCode);
                loadBrgy(data.citymunCode, data.brgyCode);
                   $('#applicantForm').modal({
                   	"backdrop": "static"
                   });
            }, complete: function () {
                $('#loading_screen').addClass('remove-loading').removeClass('loading');
            }
        })
    });


    $('.btn-uploadPhoto').click(function () {
        $('#ImageModal').modal({ "backDrop": "static" });
    });

    $('input[name=photo]').change(function () {
        console.log('triggered')
        var input = this;
        if (input.files && input.files[0]) {
            var reader = new FileReader();

            reader.onload = function (e) {
                $("#img").attr('src', e.target.result);
            }
            reader.readAsDataURL(input.files[0]);
        }
    })

    $('#updateUser').submit(function (e) {
        e.preventDefault();
        var url = $(this).attr('action');
        var data = $(this).serialize();

        $.ajax({
            url: url,
            method: 'post',
            data: data,
            beforeSend: function () {
                $('#loading_screen').addClass('loading').removeClass('remove-loading');
            },
            success: function (result) {
                if (result.success) {
                    $.bootstrapGrowl(result.msg, {
                        type: 'success'
                    });
                    sessionStorage.setItem('email', $('input[name=email]').val());
                    setTimeout(function () { location.reload() }, 1000);
                } else {
                    $.bootstrapGrowl(result.msg, { type: 'danger'})
                }
            }, complete: function () {
                $('#loading_screen').addClass("remove-loading").addClass('loading');
            }
        })
    })

    $('#uploadPhoto').submit(function (e) {
        e.preventDefault();
        url = $(this).attr('action');
        var data = new FormData(this);
        $.ajax({
            url: url,
            	// url: base_url + 'resumes/upload_photo',
            method: 'post',
            data: data,
            processData: false,
            contentType: false,
            beforeSend: function () {
                $('#loading_screen').addClass('loading').removeClass('remove-loading');
            },
            success: function (result) {
                if (result.success) {
                    $.bootstrapGrowl(result.msg, {
                        type: 'success'
                    });
                    setTimeout(function (){location.reload()}, 1000);
                } else {
                     $.bootstrapGrowl(result.msg, {
                     	type: 'danger'
                     })
                }
            }, complete: function () {
                $('#loading_screen').addClass('remove-loading').removeClass('loading');
            }
        })
    });

    $('.btnEditAcc').click(function () {
        $('#UserAccountModal').modal({ "backDrop": "static" });
    });
 
    $('#updateApplicanForm').submit(function (e) {
        e.preventDefault();

        var data = $(this).serialize();
        $.ajax({
            url: base_url + 'resumes/updatePersonalInfo',
            method: 'post',
            data: data,
            beforeSend: function ()
            {
                $('#loading_screen').addClass('loading').removeClass('remove-loading');
            }, success: function (result) {
                if (result.success) {
                      $.bootstrapGrowl(result.msg, {
                      	type: 'success'
                      });
                    setTimeout(function () { window.location = base_url +'resumes/profile'}, 1000);
                } else {
                        $.bootstrapGrowl(result.msg, {
                        	type: 'danger'
                        });
                }
            }, complete: function () {
                $('#loading_screen').addClass('remove-loading').removeClass('loading');
            }
        
        })
    });

    $('.addApplicant').click(function () {
        applicantID = null;
        console.log(applicantID)
        $("#applicantForm").modal("show");
    });

                /* performs updating applicant information */

                $(document).on('click', '.btnEditApplicant', function() {
                    applicantID = $(this).data('id');
                    $.ajax({
                        url: base_url + 'Applicant/getApplicantData',
                        method: 'post',
                        data: {
                            _applicantID: applicantID
                        },
                        beforeSend: function() {
                            $('#loading_screen').removeClass('remove-loading').addClass('loading');
                        },
                        success: function(applicant) {
                            $('input[name=name]').val(applicant.name);
                            $('input[name=email]').val(applicant.email);
                            $('input[name=contactNo]').val(applicant.contactNo)
                            $('input[name=address]').val(applicant.address);
                            $('input[name=position]').val(applicant.position);
                            $('select[name=provCode]').find('option[value="' + applicant.provCode + '"]').prop('selected', true)
                            $('select[name=gender]').find('option[value="' + applicant.gender + '"]').prop('selected', true)
                            loadCity(applicant.provCode, applicant.citymunCode);
                            loadBrgy(applicant.citymunCode, applicant.brgyCode)
                            $("#applicantForm").modal("show");
                        },
                        complete() {
                            $('#loading_screen').removeClass('loader').addClass('remove-loading');
                        }
                    })
                });
                /* 
                    Load list of applicants via datatable
                */
                var applicant_list = $('#ApplicantList').DataTable({
                    "processing": true,
                    "serverSide": true,
                    "order": [],
                    // "autoWidth": false,
                    "ajax": {
                        url: base_url + 'Applicant/applicant_list',
                        type: "post",
                    },
                    "columnDefs": [{
                        "targets": [0, 6],
                        "orderable": false,
                    }, {
                        "width": "3.33%",
                        "targets": 0,

                    }, {
                        "width": "5%",
                        "targets": 2
                    }]

                });


                /* 
                    Load list of applicants that applied job posting page via datatable
                */
                var applicant_applied_list = $('#ApplicantAppliedList').DataTable({
                    "processing": true,
                    "serverSide": true,
                    "order": [],
                    // "autoWidth": false,
                    "ajax": {
                        url: base_url + 'Applicant/applicant_applied_list',
                        type: "post",
                    },
                    "columnDefs": [{
                        "targets": [0, 6, 8],
                        "orderable": false,
                    }, {
                        "width": "3.33%",
                        "targets": 0,

                    }, {
                        "width": "5%",
                        "targets": 2
                    }, {
                        "width": "9.888%",
                        "targets": 8
                    }]

                });
                /*
                    performs reforms refer applicant that applied from job posting site
                */
    $(document).on('click', '.btnReferApplicant', function () {
        var id = $(this).data('id');

        $.ajax({
            url: base_url + 'Applicant/getApplicantAppliedData',
            method: 'post',
            data: {
                id: id
            },
            beforeSend: function () {
                $("#loading_screen").removeClass('remove-loading').addClass('loading');
            },
            success: function (result) {
                $('input[name=applicantID]').val(result.id);
                $('#applicant').text(result.name.toUpperCase());
                $('.address').text(result.address.toUpperCase());
                $('.company').text(result.company.toUpperCase());
                $('#position').text(result.position.toUpperCase())
                $('input[name=position]').val(result.posID);
                $('.placemerit').text(result.referred_placemerit);
                $('#referApplicantForm').modal({
                    'backdrop': 'static'
                })
            },
            complete: function () {
                $('#loading_screen').removeClass('loading').addClass('remove-loading');
            }
        });
    });

    $(document).on('click', '.btn-dlFile', function () {
        console.log("triggered");
    });




    $('#btnReferApplicantApplied').click(function (e) {
        e.preventDefault();
        var _data = $("#referApplicant_Form").serialize();
        console.log(_data)
        $.ajax({
            url: base_url + 'Applicant/refer_applicant',
            method: 'post',
            data: _data,
            beforeSend: function () {
                $('#loading_screen').removeClass('remove-loading').addClass('loading');
            },
            success: function (result) {
                if (result.status) {
                    $.bootstrapGrowl(result.msg, {
                        type: 'success'
                    });
                    $('#loading_screen').removeClass('loading').addClass('remove-loading');
                    applicant_applied_list.draw(false);
                    clearReferredForm();
                } else {
                    $.bootstrapGrowl(result.msg, {
                        type: 'danger'
                    });
                }
            },
            complete: function () {
                $('#loading_screen').removeClass('loading').addClass('remove-loading');
            }

        })
    });

                /*
                    performs adding applicant
                */
                $('#btnAddApplicant').click(function(e) {
                    e.preventDefault();
                    var url = (applicantID == null) ? base_url + 'Applicant/save_applicant/' : base_url + 'Applicant/save_applicant/' + applicantID;
                    var _data = $('#applicant_form').serialize();
                    $.ajax({
                        url: url,
                        method: 'post',
                        data: _data,
                        beforeSend: function() {
                            // $('#loadingScreen').removeClass('remove-loader-gear').addClass('loader-gear');
                            $('#loading_screen').removeClass('remove-loading').addClass('loading');
                        },
                        success: function(result) {
                            if (result.success) {
                                $.bootstrapGrowl(result.msg, {
                                    type: 'success'
                                });
                                clearApplicantForm()
                                    // applicant_list.ajax.reload();
                                applicant_list.draw(false);
                                $('input[name=name]').focus();
                            } else {
                                $.bootstrapGrowl(result.msg, {
                                    type: 'danger'
                                });
                            }
                        },
                        complete: function() {
                            $('#loading_screen').removeClass('loader').addClass('remove-loading');
                        }
                    })
                });
                /*
                   performs adding company name
                */

                /* 
                    performs cancelling form application 
                */
                $('#btnCancelApplicant').click(function(e) {
                    e.preventDefault();
                    clearApplicantForm();

                })

                /* 
                    Deletion of applicant
                 */
                $(document).on('click', '.btnDeleteApplicant', function() {
                    applicantID = $(this).data('id');
                    if (confirm('Deleting selected data.')) {
                        $.ajax({
                            url: base_url + 'Applicant/delete_applicant',
                            method: 'post',
                            data: {
                                applicantID: applicantID
                            },
                            beforeSend: function() {
                                $('#loading_screen').removeClass('remove-loading').addClass('loading');

                            },
                            success: function(result) {
                                if (result.success) {
                                    $.bootstrapGrowl(result.msg, {
                                        type: 'success'
                                    });
                                    clearApplicantForm()
                                    applicant_list.ajax.reload();
                                } else {
                                    $.bootstrapGrowl(result.msg, {
                                        type: 'danger'
                                    });
                                }
                            },
                            complete: function() {
                                $('#loading_screen').removeClass('loading').addClass('remove-loading');

                            }
                        })
                    }

                })


                /*
                    performs delete of selected rows of company list    
                */

                $('.btnDeleteSelectedApplicants').click(function() {
                    var applicantlist = $("#applicant_list tr");
                    var applicant_selected = $("#applicant_list input[name=selectRow]:checkbox:checked ").length;
                    var applicant_todelete = new Array();
                    if (applicant_selected > 0) {
                        for (var i = 0; i < applicantlist.length; i++) {
                            if (applicantlist.eq(i).find('input[name=selectRow]').prop('checked') == true) {
                                applicant_todelete.push({
                                    applicantID: applicantlist.eq(i).find('input[name=selectRow]').data('id')
                                })
                            }
                        }
                        if (confirm("Do want to delete selected data?")) {
                            $.ajax({
                                url: base_url + 'Applicant/delete_applicant',
                                method: 'post',
                                data: {
                                    applicantList: applicant_todelete
                                },
                                beforeSend: function() {
                                    $('#loading_screen').removeClass('remove-loading').addClass('loading');

                                },
                                success: function(result) {
                                    $('#loading_screen').removeClass('loading').addClass('remove-loading');

                                    if (result.success) {
                                        $.bootstrapGrowl(result.msg, {
                                            type: 'success'
                                        });
                                        applicant_list.ajax.reload();
                                        clearApplicantForm();
                                    } else {
                                        $.bootstrapGrowl(result.msg, {
                                            type: 'danger'
                                        });
                                    }
                                },
                                complete: function() {
                                    $('#loading_screen').removeClass('loading').addClass('remove-loading');

                                }


                            })
                        }
                    } else {
                        $.bootstrapGrowl('No applicant is selected!', {
                            type: 'warning'
                        });
                    }

                })
                //Selecting all applicants
                $('input[name=selectAll]').change(function(e) {
                    e.preventDefault();
                    var applicantlist = $("table tr");
                    if ($(this).prop('checked') == true) {
                        for (var i = 0; i < applicantlist.length; i++) {
                            applicantlist.eq(i).find('input[name=selectRow]').prop('checked', true);

                        }
                    } else {
                        for (var i = 0; i < applicantlist.length; i++) {
                            applicantlist.eq(i).find('input[name=selectRow]').prop('checked', false);

                        }
                    }

                })

                //Clearing applicant Form
                function clearApplicantForm() {
                    $('input[type=text]').val('')
                    applicantID = null;
                }


                $('select[name=provCode]').change(function() {
                    var provCode = $(this).val();
                    loadCity(provCode);
                })

                $('select[name=citymunCode]').change(function() {
                    var citymunCode = $(this).val();
                    loadBrgy(citymunCode);
                })
            }); //End document ready


        function loadCity(provCode, citymunCode) {
            $.ajax({
                url: base_url + 'Utility/loadCity',
                method: 'post',
                data: {
                    provCode: provCode
                },
                beforeSend: function() {
                    $('select[name=citymunCode]').prop('disabled', true)
                },
                success: function(result) {
                    $('select[name=citymunCode]').prop('disabled', false)
                    $('select[name=citymunCode]').html('');
                    $('select[name=citymunCode]').append('<option disabled>SELECT CITY</option>');
                    $.each(result, function(index, value) {
                        $('select[name=citymunCode]').append('<option value="' + value.citymunCode + '">' + value.citymunDesc + '</option')
                    })
                    $('select[name=citymunCode]').find('option').first().prop('selected', true);
                    $('select[name=citymunCode]').find('option[value="' + citymunCode + '"]').prop('selected', true);
                },
                complete: function() {
                    $('select[name=citymunCode]').prop('disabled', false)

                }
            })
        }


        function loadBrgy(citymunCode, brgyCode) {
            $.ajax({
                url: base_url + 'Utility/loadBrgy',
                method: 'post',
                data: {
                    citymunCode: citymunCode
                },
                beforeSend: function() {
                    $('select[name=brgyCodde]').prop('disabled', false)
                },
                success: function(result) {
                    $('select[name=brgyCodde]').prop('disabled', false)
                    $('select[name=brgyCode]').html('');
                    $('select[name=brgyCode]').append('<option disabled>SELECT BRGY</option>');
                    $.each(result, function(index, value) {
                        $('select[name=brgyCode]').append($('<option>', {
                            value: value.brgyCode,
                            text: value.brgyDesc
                        }))

                    })

                    $('select[name=brgyCode]').find('option').first().prop('selected', true);
                    $('select[name=brgyCode]').find('option[value="' + brgyCode + '"]').prop('selected', true);
                },
                complete: function() {
                    $('select[name=brgyCodde]').prop('disabled', false)

                }
            })
        }