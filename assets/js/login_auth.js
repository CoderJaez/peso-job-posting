$(document).ready(function() {
    $('#user_form input').keyup(function(e) {
        if (e.keyCode == 13) {}
    })

    $('#user_form').submit(function(e) {
        e.preventDefault();
        var _data = $(this).serialize();
        $.ajax({
            url: base_url + 'User_auth/check_user',
            method: 'post',
            data: _data,
            beforeSend: function() {
                $('#loading_screen').removeClass('remove-loading').addClass('loading');
            },
            success: function(data) {
                if (data.status) {
                    $.bootstrapGrowl(data.msg, {
                        type: 'success'
                    });
                    if (data.position == 'applicant') {
                        window.location = base_url + 'resumes/profile';
                    } else {
                        window.location = base_url + 'dashboard';
                    }
                } else {
                    $.bootstrapGrowl(data.msg, {
                        type: 'danger'
                    });
                }
                $('#loading_screen').removeClass('loading').addClass('remove-loading');
            },
            complete: function() {
                $('#loading_screen').removeClass('loading').addClass('remove-loading');
            }
        })
    })
})