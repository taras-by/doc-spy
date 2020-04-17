$(function () {

    $('.btn-submit').click(function () {
        // html5 required and jQuery submit
        $('#' + $(this).data('form_id') + ' [type="submit"]:button').trigger('click');
    });

    $('.modal-link').click(function (e) {
        e.preventDefault();
        let $button = $(this);
        let $modal = $('#simpleModal');
        let $spinner = $('#spinner');
        let url = $(this).attr('href');
        $button.prop('disabled', true);
        $button.addClass('disabled');
        $spinner.removeClass('invisible')
        $.ajax({
            url: url,
            dataType: "json"
        }).done(function (data) {
            $modal.find('.modal-body').html(data.body);
            $modal.find('.modal-title').html(data.title);
            $modal.modal();
        }).fail(function (jqXHR, textStatus, errorThrown) {
            alert('Request failed: ' + errorThrown);
        }).always(function () {
            $button.removeClass('disabled');
            $spinner.addClass('invisible')
        });
    });

});
