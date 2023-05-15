$('#btn-register').click((e) => {
    e.preventDefault();

    let form = $('#form-register').serialize();
    $.ajax({
        type: 'POST',
        url: app_url + '/register',
        data: form,
        beforeSend: function () {
            toggleLoading(true, i18n.global.processing);
            removeValidation('form-register');
        },
        success: function (res) {
            toggleLoading(false);
            responseUrl(res.data.url);
        },
        error: function (err) {
            handleError(err);
            toggleLoading(false);
        }
    })
});

$('#btn-submit-otp').click((e) => {
    e.preventDefault();

    let email = $('#form-otp #helper').val();
    $.ajax({
        type: 'POST',
        url: app_url + '/register/otp/' + email,
        data: {
            otp: $('#form-otp #otp').val(),
        },
        beforeSend: function () {
            toggleLoading(true, i18n.global.processing);
            removeValidation('form-otp');
        },
        success: function (res) {
            toggleLoading(false);
            handleSuccess(res.message);
            responseUrl(res.data.url, 1500);
        },
        error: function (err) {
            toggleLoading(false);
            handleError(err);
        }
    })
})
