$(document).ready(function() {
    var controlNo = null;
    var applicantID = null;
    var referral_report_status = false;

    /* 
        Load list of referred applicants via datatable
    */
    var referred_applicant_list = $('#ReferralList').DataTable({
        "processing": true,
        "serverSide": true,
        "order": [],
        // "autoWidth": false,
        "ajax": {
            url: base_url + 'Referrals/referral_list',
            type: "post",
        },
        "columnDefs": [{
            "targets": [0, 9],
            "orderable": false,
        }, {
            "targets": 6,
            "className": "dt-body-center"
        }]

    });
    $('.referApplicant').click(function() {
        controlNo = null;
        clearReferredForm();
        // loadJobVacancy();
        $('#referralForm').modal("show");

    })

    $(document).on('click', '.applicantName', function() {
        $('input[name=applicantName]').focus();
        $('#searchApplicant').modal("show");

    });

    $('input[name=applicantName]').keyup(function(e) {
        var applicant = $(this).val()
        var hiring_status = $('input[name=hiring_status]:checked').val();
        if (e.keyCode == 13) {
            if (applicant == "") {
                $("#applicantSearched").hide()
            } else {
                $.ajax({
                    url: base_url + 'Referrals/searched_applicant',
                    method: 'POST',
                    data: {
                        applicantName: applicant
                    },
                    beforeSend: function() {
                        $("#loading_screen").addClass('loading').removeClass('remove-loading');

                    },
                    success: function(data) {
                        $("#loading_screen").addClass('remove-loading').removeClass('loading');
                        $('input[name=applicantName]').val('')
                        $("#applicantSearched").html(data).show();
                    },
                    complete: function() {
                        $("#loading_screen").addClass('remove-loading').removeClass('loading');
                    }
                })
            }
        }

    })



    $(document).on('click', '.applicantSelected', function() {
        applicantID = $(this).data('id');
        $('input[name=applicantID]').val(applicantID);
        var hiring_status = $('input[name=hiring_status]:checked').val();
        var applicantName = $(this).text();
        $.post(base_url + 'Applicant/search_applicant', {
                _applicantID: applicantID,
                hiring_status: hiring_status
            },
            function(data) {
                if (data.referred) {
                    if (data.status == true) {

                        $("#applicantName").text(applicantName);
                        $("input[name=applicantID]").val(data.applicantID);
                        $('select[name=placemerit]').find('option[value="' + data.placemerit + '"]').prop('selected', true);
                        $('select[name=company] option').each(function() {
                            if ($(this).val() != data.companyID) {
                                $(this).remove();
                            }
                        })
                        $('select[name=company]').html(data.company);
                        $('select[name=position]').html(data.position);
                        $('select[name=remarks]').find('option[value="' + data.remarks + '"]').prop('selected', true);
                    } else {
                        $.bootstrapGrowl(data.msg, {
                            type: 'danger'
                        });
                    }
                } else {
                    console.log(data)
                    applicantID = data.id;
                    $('#address').text(data.address);
                    $('#applicantName').text(applicantName);
                }
                $('#searchApplicant').modal("hide");
                $("#applicantSearched").hide()

            })
    })

    $('select[name=placemerit]').change(function() {
        var placemerit = $(this).val();
        loadCompany(placemerit);
    })



    $('select[name=company]').change(function() {
        var companyID = $(this).val();
        loadPosition(companyID);
    })




    /*
     * Refer applicants
     */

    $('#btnReferApplicant').click(function(e) {
        e.preventDefault();
        var _data = $('#referral_form').serialize();
        _url = (controlNo == null) ? base_url + 'Referrals/refer_applicant' : base_url + 'Referrals/update_referral/' + controlNo;
        $.ajax({
            url: _url,
            method: 'post',
            data: _data,
            beforeSend: function() {
                $('#loading_screen').removeClass('remove-loading').addClass('loading');
            },
            success: function(result) {
                if (result.status) {
                    $.bootstrapGrowl(result.msg, {
                        type: 'success'
                    });
                    $('#loading_screen').removeClass('loading').addClass('remove-loading');
                    referred_applicant_list.ajax.reload();
                    clearReferredForm();
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
    });
    $('#btnCancelReferral').click(function(e) {
        e.preventDefault();
        clearReferredForm();
    });


    $('.cbTesda').click(function() {
        $('#cbTesda').prop('checked', true);
        $('#cbDole').prop('checked', false);
        $('#cbNone').prop('checked', false);
    });

    $('.cbDole').click(function() {
        $('#cbTesda').prop('checked', false);
        $('#cbDole').prop('checked', true);
        $('#cbNone').prop('checked', false);
    });
    $('.cbNone').click(function() {
        $('#cbTesda').prop('checked', false);
        $('#cbDole').prop('checked', false);
        $('#cbNone').prop('checked', true);
    });

    //PRINTING REFERRAL LETTER 
    $(document).on('click', '.btnPrintReferral', function() {
        controlNo = $(this).data('control_no');
        $.ajax({
            url: base_url + 'Referrals/generate_letter',
            method: 'post',
            data: {
                control_no: controlNo
            },
            success: function(data) {
                $('.rl-body').html("");
                $('.rl-body').html(data);
                $('#referral_letter').modal("show")

            }
        })
    });

    $(document).on('click', '#btnCancelPrint', function() {
        $('#referral_letter').modal("hide");
        console.log('clicked')
    });

    //PRINTING SELECTED REFERRALS
    $('.printSelectedReferral').click(function() {
        var referralist = $("#referral_list tr");
        var referral_selected = $("#referral_list input[name=selectRow]:checkbox:checked ").length;
        var referrals_toDelete = new Array();
        if (referral_selected > 0) {
            for (var i = 0; i < referralist.length; i++) {
                if (referralist.eq(i).find('input[name=selectRow]').prop('checked') == true) {
                    referrals_toDelete.push({
                        controlNo: referralist.eq(i).find('input[name=selectRow]').data('id')
                    })
                }
            }
            $.ajax({
                url: base_url + 'Referrals/generate_letter',
                method: 'post',
                data: {
                    referralList: referrals_toDelete
                },
                success: function(result) {
                    $('.rl-body').html("");
                    $('.rl-body').html(result);
                    $('#referral_letter').modal("show")
                }

            })
        } else {
            $.bootstrapGrowl('No Referrals selected!', {
                type: 'warning'
            });
        }
    });

    //Selecting all referred applicants
    $('input[name=selectAll]').change(function(e) {
        e.preventDefault();
        var applicantlist = $("#referral_list tr");
        if ($(this).prop('checked') == true) {
            for (var i = 0; i < applicantlist.length; i++) {
                applicantlist.eq(i).find('input[name=selectRow]').prop('checked', true);

            }
        } else {
            for (var i = 0; i < applicantlist.length; i++) {
                applicantlist.eq(i).find('input[name=selectRow]').prop('checked', false);

            }
        }

    });

    /* 
     * Editing referred Applicant information
     */
    $(document).on('click', '.btnEditReferredApplicant', function() {
        controlNo = $(this).data('control_no');
        var name = $(this).parents('tr').find('td').eq(2).text();
        console.log('Control: ' + controlNo);
        $.ajax({
            url: base_url + 'Referrals/get_referred_applicant_info',
            method: 'post',
            data: {
                control_no: controlNo
            },
            beforeSend: function() {
                $('#loading_screen').removeClass('remove-loading').addClass('loading');
            },
            success: function(data) {
                loadCompany(data.placemerit, data.companyID);
                loadPosition(data.companyID, data.posID)
                $("#applicantName").text(name);
                $("#address").text(data.address);
                $('input[name=prevPosition]').val(data.posID)
                $("input[name=applicantID]").val(data.applicantID);
                $("input[name=dateReferred]").val(data.dateReferred);
                $('select[name=placemerit]').find('option[value="' + data.placemerit + '"]').prop('selected', true);

                $('select[name=remarks]').find('option[value="' + data.remarks + '"]').prop('selected', true);
                $('.training-group').find('input[type=checkbox][value="' + data.training + '"]').prop('checked', true);
                $('#referralForm').modal("show");
            },
            complete: function() {
                $('#loading_screen').removeClass('loading').addClass('remove-loading');

            }
        })
    });

    /*
        performs delete of selected rows of referred list    
    */

    $('.btnDeleteSelectedReferrals').click(function() {
        var referral_list = $("#referral_list tr");
        var applicant_selected = $("#referral_list input[name=selectRow]:checkbox:checked ").length;
        var referrals_toDelete = new Array();
        if (applicant_selected > 0) {
            for (var i = 0; i < referral_list.length; i++) {
                if (referral_list.eq(i).find('input[name=selectRow]').prop('checked') == true) {
                    referrals_toDelete.push({
                        control_no: referral_list.eq(i).find('input[name=selectRow]').data('id'),
                        posID: applicantList.eq(i).find('input[name=selectRow]').data('pos_id')
                    })
                }
            }
            if (confirm("Do want to delete selected data?")) {
                $.ajax({
                    url: base_url + 'Referrals/delete_referral',
                    method: 'post',
                    data: {
                        referral_list: referrals_toDelete
                    },
                    success: function(result) {
                        if (result.status) {
                            $.bootstrapGrowl(result.msg, {
                                type: 'success'
                            });
                            referred_applicant_list.ajax.reload();
                        } else {
                            $.bootstrapGrowl(result.msg, {
                                type: 'danger'
                            });
                        }
                    }

                })
            }
        } else {
            $.bootstrapGrowl('No applicant is selected!', {
                type: 'warning'
            });
        }

    });
    /* 
       Deletion of applicant
    */
    $(document).on('click', '.btnDeleteReferredApplicant', function() {
        var _control_no = $(this).data('control_no');
        var posID = $(this).data('pos_id');
        if (confirm('Deleting selected data.')) {
            $.ajax({
                url: base_url + 'Referrals/delete_referral',
                method: 'post',
                data: {
                    control_no: _control_no,
                    posID: posID
                },
                success: function(result) {
                    if (result.status) {
                        $.bootstrapGrowl(result.msg, {
                            type: 'success'
                        });
                        clearReferredForm()
                        referred_applicant_list.ajax.reload();
                    } else {
                        $.bootstrapGrowl(result.msg, {
                            type: 'danger'
                        });
                    }
                }
            })
        }

    })

    function clearReferredForm() {
        controlNo = null;
        $("#applicantName").text('');
        $("#address").text("")
        $('select[name=placemerit]').find('option').first().prop('selected', true);
        $('select[name=company],select[name=position]').html('');
        $('input[name=applicantID], input[type=date]').val('');
    }



});


function cancelPrinting() {
    $('#referral_letter,').modal("hide");
}

function print_referral_letter(divName, layout) {
    var printContents = document.getElementById(divName).innerHTML;
    document.getElementById('PrintContents').style.display = "block";
    document.getElementById('PrintContents').innerHTML = printContents;
    var allElements = document.body.children;
    for (var i = 0; i < allElements.length; i++) {
        if (allElements[i].getAttribute('id') !== "PrintContents" && allElements[i].getElementsByTagName !== "SCRIPT") {
            allElements[i].style.display = "none";
        }
    }
    window.print();
    for (var i = 0; i < allElements.length; i++) {
        if (allElements[i].getAttribute('id') !== "PrintContents") {
            allElements[i].style.display = "block";
        } else {
            allElements[i].style.display = "none";
        }
    }
    $('#referralForm, #searchApplicant').css('display', 'none')
    $('script').removeAttr('style');
}

function loadCompany(placemerit, companyID) {
    $.ajax({
        url: base_url + 'Referrals/get_company_list',
        method: 'post',
        data: {
            placemerit: placemerit
        },
        success: function(result) {
            $('select[name=company]').html('');
            $('select[name=company]').html(result);
            $('select[name=company]').find('option').first().prop('selected', true);
            $('select[name=company]').find('option[value="' + companyID + '"]').prop('selected', true);
        }
    })

}


function loadPosition(companyID, posID) {
    $.ajax({
        url: base_url + 'Referrals/get_position_list',
        method: 'post',
        data: {
            companyID: companyID
        },
        success: function(result) {
            $('select[name=position]').html('');
            $('select[name=position]').html(result);
            $('select[name=position]').find('option').first().prop('selected', true);
            $('select[name=position]').find('option[value="' + posID + '"]').prop('selected', true);
        }
    })

}