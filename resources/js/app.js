require('./bootstrap');

import { Loading } from '../../public/plugins/custom/notiflix/build/notiflix-loading-aio';
import { Notify } from '../../public/plugins/custom/notiflix/build/notiflix-notify-aio';
import { Confirm } from '../../public/plugins/custom/notiflix/build/notiflix-confirm-aio';

const app_url = window.location.origin;
const api_url = window.location.origin + '/api';

function toggleLoading(isShow, text = 'Loading ...') {
    if (isShow) {
        Loading.standard(text);
    } else {
        Loading.remove();
    }
}

const handleSuccess = (message) => {
    Notify.success(message);
}

const handleError = (err) => {
    if (err.status == 422) {
        return handleValidationError(err);
    } else {
        return handleNormalError(err);
    }
}

const handleNormalError = (err) => {
    let error = err.responseJSON.message;
    Notify.failure(error);
}

function handleValidationError(err) {
    let error = err.responseJSON.errors;
    for (let k in error) {
        if ($('#' + k)) {
            $('#' + k).addClass('is-invalid');
            $('#error-' + k).text(error[k].join(', '));
            $('#error-' + k).addClass('d-block');
        }
    }
}

function removeValidation(formId) {
    let form = $('#' + formId + ' .form-control');
    for (let a = 0; a < form.length; a++) {
        let id = form[a].id;
        if (id) {
            $('#' + id).removeClass('is-invalid');
            $('#error-' + id).removeClass('d-block');
        }
    }

    let feedback = $('#' + formId + ' .invalid-feedback');
    for (let b = 0; b < feedback.length; b++) {
        feedback[b].innerHTML = '';
    }
}

const responseUrl = (url, timeout = 0) => {
    setTimeout(() => {
        window.location.href = url
    }, timeout);
}

const openGlobalModal = (url, title, footer = null) => {
    $.ajax({
        type: 'GET',
        url: url,
        beforeSend: function () {
            toggleLoading(true, i18n.global.generate_view);
        },
        success: function (res) {
            toggleLoading(false);
            $('#globalModal .modal-body').html(res.data.view);
            $('#globalModal .modal-title').html(title);
            if (footer) {
                footerModal(footer);
            }
            $('#globalModal').modal('show');

            if ($('#globalModal .onlyNumber')) {
                runRegexNumber();
            }

            if ($('#globalModal .onlyWord')) {
                runRegexWord();
            }

            if ($('#globalModal .password')) {
                runElemPassword();
            }

            if ($('#globalModal .phoneFormat')) {
                runRegexPhone();
            }
        }
    })
}

const footerModal = (data) => {
    let html = $('.' + data.target).html();
    $('.' + data.target).remove();

    $('#globalModal .modal-footer').html(html);
}

const closeGlobalModal = () => {
    $('#globalModal').modal('hide');
    $('#globalModal .modal-body').html('');
    $('#globalModal .modal-title').html('');
}

const addSpinnerText = (id) => {
    $('#' + id).html(`
        <div class="spinner-text mb-4"></div>
    `);
}

window.removeValidation = removeValidation;
window.handleValidationError = handleValidationError;
window.toggleLoading = toggleLoading;
window.app_url = app_url;
window.api_url = api_url;
window.responseUrl = responseUrl;
window.handleSuccess = handleSuccess;
window.handleError = handleError;
window.openGlobalModal = openGlobalModal;
window.footerModal = footerModal;
window.closeGlobalModal = closeGlobalModal;
window.addSpinnerText = addSpinnerText;
window.Confirm = Confirm;
