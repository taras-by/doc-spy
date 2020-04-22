$(function () {

    $('.btn-submit').click(function () {
        // html5 required and jQuery submit
        $('#' + $(this).data('form_id') + ' button[type="submit"]').trigger('click');
    });

    $('.modal-link').click(function (e) {
        e.preventDefault();
        let $button = $(this);
        let $modal = $('#simpleModal');
        let url = $(this).attr('href');
        $button.addClass('disabled');
        spinnerStart();
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
            spinnerStop();
        });
    });

    $('#sourceForm input[name="source[url]"]').change(function (e) {
        let url = $(this).val();
        try {
            let hostname = (new URL(url)).hostname;
            $('#sourceForm input[name="source[name]"]').val(hostname);
        } catch (_) {
            //
        }
    });

    $('#checkForm').click(function (e) {
        e.preventDefault();
        let $button = $(this);
        let $modal = $('#simpleModal');
        let action = $(this).data('action');
        let $sourceForm = $('#sourceForm');
        $button.addClass('disabled');
        $sourceForm.find('.is-invalid').removeClass('is-invalid');
        $sourceForm.find('.d-block').remove();
        spinnerStart();
        $.ajax({
            url: action,
            type: 'post',
            data: $sourceForm.serialize(),
            dataType: "json",
        }).done(function (data) {
            if (data.errors) {
                for (let [key, value] of Object.entries(data.errors)) {
                    $('#source_' + key).addClass('is-invalid');
                }
            } else {
                $modal.find('.modal-body').html(data.body);
                $modal.find('.modal-title').html(data.title);
                $modal.modal();
            }
        }).fail(function (jqXHR, textStatus, errorThrown) {
            alert('Request failed: ' + errorThrown);
        }).always(function () {
            $button.removeClass('disabled');
            spinnerStop();
        });
    });

    function spinnerStart() {
        $('#spinner').removeClass('invisible');
    }

    function spinnerStop() {
        $('#spinner').addClass('invisible');
    }
});
