$(document).ready(function() {
    $('.btnFilterReferralReport').click(function() {
            var _dateFrom = $('input[name=dateFrom]').val();
            var _dateTo = $('input[name=dateTo]').val();
            var _frequency = $('select[name=frequency]').val();
            var _office = $('select[name=office]').val();

            $.ajax({
                url: base_url + 'Reports/generate_referral_reports',
                method: 'post',
                data: {
                    dateFrom: _dateFrom,
                    dateTo: _dateTo,
                    office: _office,
                    frequency: _frequency
                },
                beforeSend: function() {
                    $('#loading_screen').removeClass('remove-loading').addClass('loading');
                },
                success: function(result) {
                    if (result.status) {
                        $("#referral_report").html('');
                        $('#referral').text(result.referral)
                        $("#referral_report").html(result.content);
                        $('#frequent').text(result.frequency);
                        // $("#dateFromTo").text(result.DateFromTo);
                        $('#header').attr('src', result.header);
                        $('#loading_screen').removeClass('loading').addClass('remove-loading');
                        $('.btnPrintReferralReport').removeAttr('disabled')
                        $("#printArea").show();
                    } else {
                        $.bootstrapGrowl(result.msg, {
                            type: 'danger'
                        });
                    }
                },
                complete: function() {
                    $('#loading_screen').removeClass('loading').addClass('remove-loading');
                }
            });
        })
        //Filter Referral Report

    //Filter Placement Report
    $('.btnFilterPlacementReport').click(function() {
        var _dateFrom = $('input[name=dateFrom]').val();
        var _dateTo = $('input[name=dateTo]').val();
        var _frequency = $('select[name=frequency]').val();
        var _office = $('select[name=office]').val();

        $.ajax({
            url: base_url + 'Reports/generate_placement_report',
            method: 'post',
            data: {
                dateFrom: _dateFrom,
                dateTo: _dateTo,
                office: _office,
                frequency: _frequency
            },
            beforeSend: function() {
                $('#loading_screen').removeClass('remove-loading').addClass('loading');
            },
            success: function(result) {
                if (result.status) {
                    $("#referral_report").html('');
                    $('#placement').text(result.placement)
                    $("#referral_report").html(result.content);
                    $('#frequent').text(result.frequency);
                    // $("#dateFromTo").text(result.DateFromTo);
                    $('#header').attr('src', result.header);
                    $('#loading_screen').removeClass('loading').addClass('remove-loading');
                    $('.btnPrintReferralReport').removeAttr('disabled')
                    $("#printArea").show();
                } else {
                    $.bootstrapGrowl(result.msg, {
                        type: 'danger'
                    });
                }
            },
            complete: function() {
                $('#loading_screen').removeClass('loading').addClass('remove-loading');
            }
        });
    })

    //Filter Job Solicited Report
    $('.btnFilterJobSolicitedReport').click(function() {
        var _dateFrom = $('input[name=dateFrom]').val();
        var _dateTo = $('input[name=dateTo]').val();
        var _frequency = 'week';
        var _office = 'peso';

        $.ajax({
            url: base_url + 'Reports/generate_jobsolicitation_reports',
            method: 'post',
            data: {
                dateFrom: _dateFrom,
                dateTo: _dateTo,
                office: _office,
                frequency: _frequency
            },
            beforeSend: function() {
                $('#loading_screen').removeClass('remove-loading').addClass('loading');
            },
            success: function(result) {
                if (result.status) {
                    $("#referral_report").html('');
                    $("#referral_report").html(result.content);
                    $('#frequent').text(result.frequency);
                    // $("#dateFromTo").text(result.DateFromTo);
                    $('#loading_screen').removeClass('loading').addClass('remove-loading');
                    $('.btnPrintReferralReport').removeAttr('disabled')
                    $("#printArea").show();
                    console.log(result.content)
                } else {
                    $.bootstrapGrowl(result.msg, {
                        type: 'danger'
                    });
                }
            },
            complete: function() {
                $('#loading_screen').removeClass('loading').addClass('remove-loading');
            }
        });
    })
})