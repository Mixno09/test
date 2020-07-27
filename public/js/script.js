(function ($) {
    $('#registration_form').on('submit', function (event) {
        event.preventDefault();

        const password = $('#inputPassword').val();
        const confirmPassword = $('#confirmPassword').val();
        if (password !== confirmPassword) {
            alert('Пароли должны совпадать');
            return;
        }

        $.ajax({
            url: $(this).attr('action'),
            method: $(this).attr('method'),
            data: new FormData(this),
            dataType: 'json',
            processData: false,
            contentType: false,
            cache: false,
            beforeSend: () => {
                $(this).find('input, button').prop('disabled', true);
            },
            success: () => {
                window.location.href = '/login';
            },
            error: (response) => {
                if (response.status === 422) {
                    const message = response.responseJSON.join('\n');
                    alert(message);
                    return;
                }
                alert('Что-то случилось');
            },
            complete: () => {
                $(this).find('input, button').prop('disabled', false);
            }
        })
    });
    $('#login_form').on('submit', function (event) {
        event.preventDefault();

        $.ajax({
            url: $(this).attr('action'),
            method: $(this).attr('method'),
            data: new FormData(this),
            dataType: 'json',
            processData: false,
            contentType: false,
            cache: false,
            beforeSend: () => {
                $(this).find('input, button').prop('disabled', true);
            },
            success: () => {
                window.location.href = '/';
            },
            error: (response) => {
                if (response.status === 422) {
                    const message = response.responseJSON.join('\n');
                    alert(message);
                    return;
                }
                alert('Что-то случилось');
            },
            complete: () => {
                $(this).find('input, button').prop('disabled', false);
            }
        })
    });
})(jQuery);

