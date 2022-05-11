$(document).ready(function() {
    company_list();
    load_company();
    loadCKEDITOR();
    var jsID = null;

    /*
        Load list of job vacancy via datatable
    */
    var job_vacancy = $('#job_vacancy').DataTable({
        "processing": true,
        "serverSide": true,
        "order": [],
        "ajax": {
            url: base_url + '/JobSolicitation/job_vacancy_list',
            type: "post",
        },
        "columnDefs": [{
            "targets": [0, 6, 7, 8],
            "orderable": false,
        }]

    });

    function loadCKEDITOR() {
        if ($('#job_desc').prop("id") == 'job_desc') {
            CKEDITOR.replace('job_desc');
        }

        if ($('#job_req').prop("id") == 'job_req') {
            CKEDITOR.replace('job_req');
        }

    }
    $('#btnAddJS').click(function() {
        clearJobSolicitedForm()
        CKEDITOR.instances['job_desc'].setData('')
        CKEDITOR.instances['job_req'].setData('')
        $('#JobSolicitation').modal('show');
        $('input[name=jsID]').val('');
    })

    $(document).on('click', '.close', function() {
        $(this).parent('._pos').remove();
        console.log('click')
    })

    $('.btnAddMorePosition').click(function(e) {
        e.preventDefault();
        $('.position').append('<div class="_pos"><br><input class="form-control" name="position[]"><span  class="fa fa-close close"></span></div>')
    })

    $('input[name=company]').keyup(function() {
        if ($(this).val() == "") {
            $('input[name=companyID]').val('');
            $('input[name=companyID]').removeAttr('readonly');
            $('input[name=address]').removeAttr('readonly');
            $('input[name=manager]').removeAttr('readonly');
            $('select[name=placemerit]').removeAttr('readonly');

        }
    })

    $(document).on('click', '.btnViewJS', function() {
        var jsID = $(this).data('id');
        $.ajax({
            url: base_url + 'JobSolicitation/get_jobsolicited_data',
            method: 'post',
            data: {
                id: jsID
            },
            beforeSend: function() {
                $('#loading_screen').removeClass('remove-loading').addClass('loading');
            },
            success: function(result) {
                $('input[name=jsID]').val(jsID)
                $('#loading_screen').removeClass('loader').addClass('remove-loading');
                $('.company').text(result.company.toUpperCase())
                $('.vacancy').text(result.vacancy);
                $('.position').text(result.position.toUpperCase());
                $('.job_description').html(result.job_description)
                $('.requirements').html(result.requirements)
                $('input[name=dateSolicited]').val(result.dateSolicited);
                $('#view_js').modal('show');
            },
            complete: function() {
                $('#loading_screen').removeClass('loading').addClass('remove-loading');

            }
        })
    })

    $(document).on('click', '.company_list li', function() {
        var id = $(this).val();
        var name = $(this).text();
        var address = $(this).data('address');
        var manager = $(this).data('manager');
        var placemerit = $(this).data('placemerit');

        $('input[name=company]').val(name);
        $('input[name=companyID]').val(id).attr('readonly', true);
        $('input[name=address]').val(address).attr('readonly', true);
        $('input[name=manager]').val(manager).attr('readonly', true);
        $('select[name=placemerit]').find('option[value="' + placemerit + '"]').prop('selected', true).attr('disabled', true)
        $('select[name=placemerit]').attr('readonly', true)
    })


    $(document).on('click', '.btnEditJobSolicited', function() {
        var jsID = $(this).data('id');
        $.ajax({
            url: base_url + 'JobSolicitation/get_jobsolicited_data',
            method: 'post',
            data: {
                id: jsID
            },
            beforeSend: function() {
                $('#loading_screen').removeClass('remove-loading').addClass('loading');
            },
            success: function(result) {
                load_position(result.companyID, result.posID);
                $('input[name=jsID]').val(jsID)
                $('#loading_screen').removeClass('loader').addClass('remove-loading');
                $('select[name=js_company]').find('option[value="' + result.companyID + '"]').prop('selected', true);
                CKEDITOR.instances['job_desc'].setData(result.job_description)
                CKEDITOR.instances['job_req'].setData(result.requirements)
                    // $('textarea[name=job_desc]').html(result.job_description);
                    // $('textarea[name=job_req]').html(result.requirements);
                $('input[name=vacancy]').val(result.vacancy);
                $('input[name=referral]').val(result.no_of_referral);
                $('input[name=dateSolicited]').val(result.dateSolicited);
                $('select[name=js_position]').has('option[value="' + result.posID + '"]').prop('selected', true);
                $('#JobSolicitation').modal('show');
            },
            complete: function() {
                $('#loading_screen').removeClass('loading').addClass('remove-loading');

            }
        })
    })

    $(document).on('click', '.btnDeleteJobSolicited', function() {
        var id = $(this).data('id');
        if (confirm("Do you want to delete selected row?")) {
            $.ajax({
                url: base_url + 'JobSolicitation/delete_js',
                method: 'post',
                data: {
                    id: id
                },
                beforeSend: function() {
                    $('#loading_screen').removeClass('remove-loading').addClass('loading');
                },
                success: function(result) {
                    if (result.status) {
                        $.bootstrapGrowl(result.msg, {
                            type: 'success'
                        });
                        job_vacancy.ajax.reload();
                    }
                },
                complete: function() {
                    $('#loading_screen').removeClass('loader').addClass('remove-loading');

                }
            })
        }
    })

    /*
        performs delete of selected rows of job vacancy list    
    */

    $('#btnDeleteJS').click(function() {
        var job_vacancy_list = $("#job_vacancy tr");
        var row_selected = $("#job_vacancy input[name=selectRow]:checkbox:checked ").length;
        var jv_list = new Array();
        if (row_selected > 0) {
            for (var i = 0; i < job_vacancy_list.length; i++) {
                if (job_vacancy_list.eq(i).find('input[name=selectRow]').prop('checked') == true) {
                    jv_list.push({
                        id: job_vacancy_list.eq(i).find('input[name=selectRow]').data('id')
                    })
                }
            }
            if (confirm("Do want to delete selected data?")) {
                $.ajax({
                    url: base_url + 'JobSolicitation/delete_js',
                    method: 'post',
                    data: {
                        job_vacancy_list: jv_list
                    },
                    beforeSend: function() {
                        $('#loading_screen').removeClass('remove-loading').addClass('loading');
                    },
                    success: function(result) {
                        if (result.status) {
                            $.bootstrapGrowl(result.msg, {
                                type: 'success'
                            });
                            job_vacancy.ajax.reload();
                        }
                    },
                    complete: function() {
                        $('#loading_screen').removeClass('loader').addClass('remove-loading');

                    }

                })
            }
        } else {
            $.bootstrapGrowl('No applicant is selected!', {
                type: 'warning'
            });
        }

    });

    $('.btnAddPosition').click(function(e) {
        e.preventDefault();
        $('#Company_Position').modal('show')
    })

    $('select[name=js_company]').change(function() {
        var companyID = $(this).val();
        load_position(companyID);
    }); //


    $('#JS_form').submit(function(e) {
        e.preventDefault();
        CKEDITOR.instances['job_desc'].updateElement(); //CKEditor  bilgileri  aktarıyor.
        CKEDITOR.instances['job_req'].updateElement(); //CKEditor  bilgileri  aktarıyor.
        var data = $(this).serialize();
        url = ($('input[name=jsID]').val() != '') ? base_url + 'JobSolicitation/update_js' : base_url + 'JobSolicitation/add_newJS';
        $.ajax({
            url: url,
            method: 'post',
            data: data,
            beforeSend: function() {
                $("#loading_screen").addClass('loading').removeClass('remove-loading');

            },
            success: function(result) {
                if (result.status) {
                    $.bootstrapGrowl(result.msg, {
                        type: 'success'
                    });
                    clearJobSolicitedForm()
                    job_vacancy.ajax.reload();
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

    $('#btnCanceJS').click(function(e) {
        e.preventDefault();
    })

    $('#CompanyPosition_form').submit(function(e) {
        e.preventDefault();
        var data = $(this).serialize()
        console.log(data)
        $.ajax({
            url: base_url + 'JobSolicitation/addCompany_Position',
            method: 'post',
            data: data,
            beforeSend: function() {
                $("#loading_screen").addClass('loading').removeClass('remove-loading');

            },
            success: function(result) {
                if (result.status) {
                    $.bootstrapGrowl(result.msg, {
                        type: 'success'
                    });
                    $("#loading_screen").addClass('remove-loading').removeClass('loading');
                    clearCompanyPositionForm()
                    company_list();
                    load_company();
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
    })
})

function company_list(placemerit) {
    $.ajax({
        url: base_url + 'JobSolicitation/get_company_list',
        method: 'post',
        data: {
            placemerit: placemerit
        },
        success: function(result) {
            $('.company_list').html('');
            $('.company_list').html(result);
        }
    })
}

function clearCompanyPositionForm() {
    $('#CompanyPosition_form input[type=text], input[type=hidden] ').val('')
    $('#CompanyPosition_form ._post').remove();

}

function clearJobSolicitedForm() {
    $('#JS_form select').html('')
    load_company();
    $('#JS_form textarea, input[type=number], input[type=hidden], input[type=date]').val('')
    CKEDITOR.instances['job_desc'].setData('')
    CKEDITOR.instances['job_req'].setData('')
}


function load_company($companyID) {
    $.ajax({
        url: base_url + 'JobSolicitation/load_company',
        method: 'post',
        success: function(result) {
            $('select[name=js_company]').html(result);
        }
    });
}

function load_position(companyID, posID) {
    $.ajax({
        url: base_url + 'JobSolicitation/load_position',
        method: 'post',
        data: {
            companyID: companyID
        },
        success: function(result) {
            $('select[name=js_position]').html('');
            $('select[name=js_position]').html(result);
            $('select[name=js_position]').find('option[value="' + posID + '"]').prop('selected', true);
        }
    })
}