require('./bootstrap');

import { Loading } from '../../public/plugins/custom/notiflix/build/notiflix-loading-aio';
import { Notify } from '../../public/plugins/custom/notiflix/build/notiflix-notify-aio';

const app_url = window.location.origin;

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
        }
    }
}

function removeValidation(formId) {
    let form = $('#' + formId + ' .form-control');
    for (let a = 0; a < form.length; a++) {
        let id = form[a].id;
        $('#' + id).removeClass('is-invalid');
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

window.removeValidation= removeValidation;
window.handleValidationError = handleValidationError;
window.toggleLoading = toggleLoading;
window.app_url = app_url;
window.responseUrl = responseUrl;
window.handleSuccess = handleSuccess;
window.handleError = handleError;
