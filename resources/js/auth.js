const { default: Timer } = require("easytimer.js");



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

/**
 * Function to send OTP via whatsapp
 */
const sendOtp = (phone) => {
    $.ajax({
        type: 'POST',
        url: app_url + '/otp/send',
        data: {
            phone: phone,
        },
        beforeSend: function() {
            toggleLoading(true, i18n.global.sending);
            $('#send-otp-whatsapp-btn').prop('disabled', true);
        },
        success: function (res) {
            toggleLoading(false);
            $('#row-otp-whatsapp').removeClass('d-none');
            $('#btn-submit-otp').prop('disabled', false);
            $('#send-otp-whatsapp-btn').prop('disabled', true);
            setupTimer(60);
        },
        error: function (err) {
            console.log('err',err);
            $('#send-otp-whatsapp-btn').prop('disabled', false);
            toggleLoading(false);
            handleError(err);
        }
    })
}

const verifiedEmailOtp = () => {
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
}

const verifiedPhoneOtp = () => {
    $.ajax({
        type: 'POST',
        url: app_url + '/otp/whatsapp/validate',
        data: {
            email: $('#helper').val(),
            otp: $('#otp').val(),
        },
        beforeSend: function () {
            toggleLoading(true, i18n.global.processing);
            removeValidation('form-otp-whatsapp');
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
}

const setupTimer = (seconds) => {
    var timer = new Timer({
        countdown: true,
    });
    timer.start({
        startValues: {
            seconds: seconds,
        },
        target: {
            seconds: 0,
        }
    });
    $('#send-otp-whatsapp-btn').html(timer.getTimeValues().toString());

    timer.addEventListener('secondsUpdated', function (e) {
        $('#send-otp-whatsapp-btn').html(timer.getTimeValues().toString());
    });

    timer.addEventListener('targetAchieved', function (e) {
        $('#send-otp-whatsapp-btn').prop('disabled', false);
        $('#send-otp-whatsapp-btn').text(i18n.global.send + ' ' + i18n.global.otp);
    });
}

window.setupTimer = setupTimer;
window.sendOtp = sendOtp;
window.verifiedPhoneOtp = verifiedPhoneOtp;
window.verifiedEmailOtp = verifiedEmailOtp;
