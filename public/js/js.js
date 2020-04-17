$(function () {
    $('.btn-submit').click(function () {
        // html5 required and jQuery submit
        $('#' + $(this).data('form_id') + ' [type="submit"]:button').trigger('click');
    });
});
