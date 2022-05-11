$(document).ready(function() {
    $('[data-mask]').inputmask()

    $('.btnAddNewOfw').click(function() {
        $('input[name=ofwID]').val('');
        $('#ofw_modal').modal('show');
    })

    $('.btnCancelOfw').click(function(e) {
        e.preventDefault();
        clearOfwForm();
    })

    /* 
        Load list of applicants via datatable
    */
    var ofw_list = $('#ofwList').DataTable({
        "processing": true,
        "serverSide": true,
        "order": [],
        // "autoWidth": false,
        "ajax": {
            url: base_url + 'Ofw/ofw_list',
            type: "post",
        },
        "columnDefs": [{
            "targets": [0, 10],
            "orderable": false,
        }, {
            "width": "3.33%",
            "targets": 0,

        }, {
            "width": "5%",
            "targets": 2
        }]

    });

    $('#ofw_form').submit(function(e) {
        e.preventDefault();
        var _tempData;
        $('input[name=yearFromTo]').inputmask('remove');
        $('input[name=contactNo]').inputmask('remove');

        var ofw_data = $(this).serialize();
        url = ($('input[name=ofwID]').val() == '') ? 'Ofw/add_ofw' : 'Ofw/update_ofw'
        $.ajax({
            url: base_url + url,
            method: 'post',
            dataType: 'json',
            data: ofw_data,
            beforeSend: function() {
                $('#loading_screen').removeClass('remove-loading').addClass('loading');
            },
            success: function(result) {
                if (result.status) {
                    $.bootstrapGrowl(result.msg, {
                        type: 'success'
                    });
                    if ($('input[name=ofwID]').val() == '') {
                        ofw_list.draw()
                    } else {
                        ofw_list.draw(false)
                    }
                    clearOfwForm()
                } else {
                    $.bootstrapGrowl(result.msg, {
                        type: 'danger'
                    });
                }
            },
            complete: function() {
                $('#loading_screen').removeClass('loading').addClass('remove-loading')
                $('[data-mask]').inputmask()
            }
        }); // Ajax request
    });


    function clearOfwForm() {
        $('input[type=text],input[type=hidden]').val('');
        $('select[name=brgyCode]').find('option').first().prop('selected', true);
    }

}); //End of Ready Document