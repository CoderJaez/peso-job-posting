$(document).ready(function() {
    var companyID = null;
    var positionID = null;
    $('.addCompany').click(function() {
        $("#companyForm").modal("show");
        companyID = '';
        clearCompanyForm();
    });
    /* Editing company details */
    $(document).on('click', '.btnEditCompany', function() {
        companyID = $(this).data('id');
        var provCode = $(this).parents('tr').find('td').eq(0).find('input[name=selectRow]').data('provcode');
        var citymunCode = $(this).parents('tr').find('td').eq(0).find('input[name=selectRow]').data('citymuncode');
        var brgyCode = $(this).parents('tr').find('td').eq(0).find('input[name=selectRow]').data('brgycode');
        var address = $(this).parents('tr').find('td').eq(0).find('input[name=selectRow]').data('address')
        var placemerit = $(this).parents('tr').find('td').eq(1).text();
        var company_name = $(this).parents('tr').find('td').eq(2).text();
        var manager_name = $(this).parents('tr').find('td').eq(4).text();
        $('select[name=placemerit]').find('option[value="' + placemerit + '"]').prop('selected', true);
        $('select[name=provCode]').find('option[value="' + provCode + '"]').prop('selected', true);
        $('input[name=company_name]').val(company_name);
        $('input[name=company_address]').val(address);
        $('input[name=manager_name]').val(manager_name);
        loadCity(provCode, citymunCode);
        loadBrgy(citymunCode, brgyCode);
        $("#companyForm").modal("show");
    });

    /*Deleting company details */
    $(document).on('click', '.btnDeleteCompany', function() {
        companyID = $(this).data('id');
        if (confirm("You want to delete this data?")) {
            $.ajax({
                url: base_url + 'Settings/delete_company',
                method: 'post',
                data: {
                    company_id: companyID
                },
                success: function(result) {
                    if (result.success) {
                        $.bootstrapGrowl(result.msg, {
                            type: 'success'
                        });
                        company_list.ajax.reload();
                        clearCompanyForm();
                    } else {
                        $.bootstrapGrowl(result.msg, {
                            type: 'danger'
                        });
                    }
                }
            })
        }
    })

    /*
        performs delete of selected rows of company list    
    */

    $('.btnDeleteSelectedCompany').click(function() {
        var companylist = $("#company_list tr");
        var company_selected = $("#company_list input[name=inputSelectCompany]:checkbox:checked ").length;
        var company_todelete = new Array();
        if (company_selected > 0) {
            for (var i = 0; i < companylist.length; i++) {
                if (companylist.eq(i).find('input[name=inputSelectCompany]').prop('checked') == true) {
                    company_todelete.push({
                        companyID: companylist.eq(i).find('input[name=inputSelectCompany]').data('id')
                    })
                }
            }
            $.ajax({
                url: base_url + 'Settings/delete_company',
                method: 'post',
                data: {
                    companyList: company_todelete
                },
                success: function(result) {
                    if (result.success) {
                        $.bootstrapGrowl(result.msg, {
                            type: 'success'
                        });
                        company_list.ajax.reload();
                        clearCompanyForm();
                    } else {
                        $.bootstrapGrowl(result.msg, {
                            type: 'danger'
                        });
                    }
                }

            })
        } else {
            $.bootstrapGrowl('No company is selected!', {
                type: 'info'
            });
        }

    })

    /*
        performs adding company name
    */
    $('#btnAddCompany').click(function(e) {
        e.preventDefault();

        var _data = $('#company_form').serialize();
        $.ajax({
            url: base_url + 'Settings/add_company/' + companyID,
            method: 'post',
            data: _data,
            beforeSend: function() {
                $('#loading_screen').removeClass('remove-loader-gear').addClass('loader-gear');
            },
            success: function(result) {
                $('input[name=csrf_test_name]').val(result.token);
                if (result.success) {
                    companyID = '';
                    $.bootstrapGrowl(result.msg, {
                        type: 'success'
                    });
                    $('#loading_screen').removeClass('loader-gear').addClass('remove-loader-gear');
                    company_list.ajax.reload();
                    clearCompanyForm();
                } else {
                    $.bootstrapGrowl(result.msg, {
                        type: 'danger'
                    });
                }
            },
            complete: function() {
                $('#loading_screen').removeClass('loader-gear').addClass('remove-loader-gear');

            }
        })
    });
    /*
       performs adding company name
    */

    /* performs cancelling adding form
     */
    $('#btnCancelCompany').click(function(e) {
        e.preventDefault();
        clearCompanyForm();
        $('#companyForm').modal("hide");
    })

    //Clearing form
    function clearCompanyForm() {
        $("input[type=text]").val('');
        $('select[name=provCode]').find('option').first(0).prop('selected', true);
        $('select[name=citymunCode],select[name=brgyCode]').val('')
    }


    //load company list
    var company_list = $('#companyList').DataTable({
        "processing": true,
        "serverSide": true,
        "order": [],
        "ajax": {
            url: base_url + 'Settings/display_company_list/',
            type: "POST",
        },
        "columnDefs": [{
            "targets": [0, 6],
            "orderable": false
        }, ]

    });


    /*
     *
     * 
     *  Position module
     * 
     * 
     * 
     */

    //load position list
    var positionList = $('#positionList').DataTable({
        "processing": true,
        "serverSide": true,
        "order": [],
        "ajax": {
            url: base_url + 'Settings/position_list',
            type: "POST",
        },
        "columnDefs": [{
            "targets": [0, 4],
            "orderable": false
        }, ]

    });

    $('#btnSavePosition').click(function(e) {
        e.preventDefault();
        var _data = $('#positionForm').serialize();
        var _url = null;
        if (positionID == null) {
            _url = base_url + 'Settings/save_position';
        } else {
            _url = base_url + 'Settings/save_position/' + positionID;
        }
        console.log(_url)
        $.ajax({
            url: _url,
            method: 'post',
            data: _data,
            beforeSend: function() {
                $('#btnSavePosition').addClass('disabled');
            },
            success: function(data) {
                if (data.status) {
                    $.bootstrapGrowl(data.msg, {
                        type: 'success'
                    });
                    positionList.ajax.reload();
                    clearPositionForm();
                } else {
                    $.bootstrapGrowl(data.msg, {
                        type: 'danger'
                    });
                }
            },
            complete: function() {
                $('#btnSavePosition').removeClass('disabled');

            }

        })
    })
    $('#btnCancelPosition').click(function(e) {
            e.preventDefault();
        })
        /* Editing position details */
    $(document).on('click', '.btnEditPosition', function() {
        positionID = $(this).data('id');
        var position = $(this).parents('tr').find('td').eq(2).text();
        var companyID = $(this).data('companyid');
        $('select[name=companyID]').find("option[value=" + companyID + "]").prop('selected', true);
        $('input[name=position]').val(position);
        $("#companyForm").modal("show");
    });

    function clearPositionForm() {
        $('#positionForm input[type=text]').val('');
        positionID = null;
    }
    /*Deleting company details */
    $(document).on('click', '.btnDeletePosition', function() {
        positionID = $(this).data('id');
        if (confirm("You want to delete this data?")) {
            $.ajax({
                url: base_url + 'Settings/delete_position',
                method: 'post',
                data: {
                    posID: positionID
                },
                success: function(result) {
                    if (result.success) {
                        $.bootstrapGrowl(result.msg, {
                            type: 'success'
                        });
                        positionList.ajax.reload();
                        clearCompanyForm();
                    } else {
                        $.bootstrapGrowl(result.msg, {
                            type: 'danger'
                        });
                    }
                }
            })
        }
    })

    /* selects to be delete */
    $('input[name=cbSelectPosition]').change(function() {
        var pList = $("#position_list tr");
        console.log(pList.length)
        if ($(this).prop('checked') == true) {
            for (var i = 0; i < pList.length; i++) {
                pList.eq(i).find('input[name=inputSelectPosition]').prop('checked', true);

            }
        } else {
            for (var i = 0; i < pList.length; i++) {
                pList.eq(i).find('input[name=inputSelectPosition]').prop('checked', false);

            }
        }

    })

    /*
           performs delete of selected rows of position list    
       */

    $('.btnDeleteSelectedPosition').click(function() {
        var position_list = $("#position_list tr");
        var position_selected = $("#position_list input[name=inputSelectPosition]:checkbox:checked ").length;
        var position_todelete = new Array();
        if (position_selected > 0) {
            for (var i = 0; i < position_list.length; i++) {
                if (position_list.eq(i).find('input[name=inputSelectPosition]').prop('checked') == true) {
                    position_todelete.push({
                        positionID: position_list.eq(i).find('input[name=inputSelectPosition]').data('id')
                    })
                }
            }
            if (confirm("Do want to delete selected data?")) {
                $.ajax({
                    url: base_url + 'Settings/delete_position',
                    method: 'post',
                    data: {
                        positionList: position_todelete
                    },
                    success: function(result) {
                        if (result.success) {
                            $.bootstrapGrowl(result.msg, {
                                type: 'success'
                            });
                            positionList.ajax.reload();
                            clearApplicantForm();
                        } else {
                            $.bootstrapGrowl(result.msg, {
                                type: 'danger'
                            });
                        }
                    }

                })
            }
        } else {
            $.bootstrapGrowl('No Job position is selected!', {
                type: 'warning'
            });
        }

    })
})