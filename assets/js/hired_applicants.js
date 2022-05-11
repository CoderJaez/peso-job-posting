$(document).ready(function() {
    var placementID = null;


    /* 
        Load list of hired applicants via datatable
    */
    var hired_applicant_list = $('#hired_applicant_list').DataTable({
        "processing": true,
        "serverSide": true,
        "order": [],
        // "autoWidth": false,
        "ajax": {
            url: base_url + 'Placement/hired_applicant_list',
            type: "post",
        },
        "columnDefs": [{
            "targets": [0, 6, 7],
            "orderable": false,
        }, {
            "targets": [6],
            "className": "dt-body-center"
        }]

    });
    $("#btnAddNewHiredApplicant").click(function() {
        clearHiring_form();
        $("#hired_applicantForm").modal('show');
    })

    $('.hiring_status').click(function() {
        $(this).find('input[type=radio]').prop('checked', true)
    });

    $(document).on('click', '.btnEditHiredApplicant', function() {
        placementID = $(this).val();
        $.post(base_url + 'Placement/getHiredApplicantData', {
            placementID: placementID
        }, function(data) {
            loadCompany(data.placemerit, data.companyID);
            loadPosition(data.companyID, data.posID);
            $('#hiring_form').find('input[name=hiring_status][value="' + data.hiring_status + '"]').prop('checked', true);
            $('input[name=applicantID]').val(data.applicantID);
            $('input[name=prevPosition]').val(data.posID);
            $('#applicantName').text(data.applicantName);
            $('select[name=placemerit]').find('option[value="' + data.placemerit + '"]').prop('selected', true);
            $('input[name=dateHired]').val(data.dateHired);
            $('select[name=remarks]').find('option[value="' + data.remarks + '"]').prop('selected', true);
            $('input[name=placementID').val(data.placementID);
            $("#hired_applicantForm").modal('show');
        })
    })

    $('#hiring_form').submit(function(e) {
        e.preventDefault();
        var url = (placementID != null) ? 'update_HiredApplicant/' + placementID : 'hire_newApplicant';
        var data = $(this).serialize();
        $.ajax({
            url: base_url + '/Placement/' + url,
            method: 'post',
            data: data,
            beforeSend: function() {
                $("#loading_screen").addClass('loading').removeClass('remove-loading');
            },
            success: function(result) {
                console.log(result);
                if (result.status) {
                    $.bootstrapGrowl(result.msg, {
                        type: 'success'
                    });
                    clearHiring_form();
                    $("#loading_screen").addClass('remove-loading').removeClass('loading');
                    hired_applicant_list.ajax.reload();
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

    //deled selected applicant

    $(document).on('click', '.btnDeleteHiredApplicant', function() {
        var id = $(this).val();
        var posID = $(this).data('pos_id');
        if (confirm('Do want to delete selected row/s?')) {
            $.ajax({
                url: base_url + 'Placement/delete_applicant',
                method: 'post',
                data: {
                    placementID: id,
                    posID: posID
                },
                beforeSend: function() {
                    $("#loading_screen").addClass('loading').removeClass('remove-loading');
                },
                success: function(result) {
                    if (result.status) {
                        $.bootstrapGrowl(result.msg, {
                            type: 'success'
                        });
                        hired_applicant_list.ajax.reload();
                    }
                },
                complete: function() {
                    $("#loading_screen").addClass('remove-loading').removeClass('loading');
                }
            });
        }
    })

    $('#btnSelectedDeleteHiredApplicant').click(function() {
        var applicantList = $('table tr');
        var applicant_selected = $('table tr input[name=selectRow]:checked').length;
        var applicants_todelete = new Array();

        for (var i = 0; i < applicantList.length; i++) {
            if (applicantList.eq(i).find('input[name=selectRow]').prop('checked') == true) {
                applicants_todelete.push({
                    placementID: applicantList.eq(i).find('input[name=selectRow]').val(),
                    posID: applicantList.eq(i).find('input[name=selectRow]').data('pos_id')
                })
            }
        }

        if (applicant_selected > 0) {
            if (confirm('Do want to delete selected row/s?')) {
                $.ajax({
                    url: base_url + 'Placement/delete_applicant',
                    method: 'post',
                    data: {
                        applicant_list: applicants_todelete
                    },
                    beforeSend: function() {
                        $("#loading_screen").addClass('loading').removeClass('remove-loading');
                    },
                    success: function(result) {
                        if (result.status) {
                            $.bootstrapGrowl(result.msg, {
                                type: 'success'
                            });
                            hired_applicant_list.ajax.reload();
                        }
                    },
                    complete: function() {
                        $("#loading_screen").addClass('remove-loading').removeClass('loading');
                    }
                });
            }
        } else {
            $.bootstrapGrowl('No applicant/s selected', {
                type: 'warning'
            });
        }
    });

});


function clearHiring_form() {
    placementID = null;
    $('#hiring_form input[type=text], input[type=hidden], input[type=date]').val('')
    $('#applicantName').text('');
    $('select[name=remarks]').find('option').first().prop('selected', true);
    $('#hiring_form select[name=company], select[name=position]').html('')
    $('select[name=placemerit]').find('option').first().prop('selected', true);
    $('input[name=hiring_status]').prop('checked', false);

};