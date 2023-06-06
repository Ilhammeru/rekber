import tippy from 'tippy.js';
import 'tippy.js/dist/tippy.css';
import 'tippy.js/themes/light.css';
import 'tippy.js/animations/scale.css';
import { Confirm } from '../../public/plugins/custom/notiflix/build/notiflix-confirm-aio';

var dt, dtTrx;
var columns = [
    {data: 'id',
        render: function (data, type, row, meta) {
            return meta.row + meta.settings._iDisplayStart + 1;
        },
        width: '5%',
        className: 'text-center'
    },
    {data: 'namedata', name: 'name'},
    {data: 'emaildata', name: 'email'},
    {data: 'joined_at', name: 'joined_at'},
    {data: 'balance', name: 'balance'},
    {data: 'action', name: 'action', className: 'text-center', orderable: false},
    {data: 'created_at', name: 'created_at', visible: false},
];

const init = (tableId, status) => {
    dt = $('#' + tableId).DataTable({
        processing: true,
        serverSide: true,
        responsive: true,
        scrollX: true,
        ajax: {
            url: app_url + '/users/ajax/' + status,
        },
        columns: columns,
        // drawCallback: function(settings, json) {
        //     tippy(document.querySelectorAll('.action-category'), {
        //         content(reference) {
        //             const id = reference.getAttribute('data-template');
        //             const template = document.getElementById('action-' + id);
        //             return template.innerHTML;
        //         },
        //         allowHTML: true,
        //         theme: 'light',
        //         interactive: true,
        //         trigger: 'click',
        //         animation: 'scale',
        //     });
        // },
        order: [[3, 'desc']],
    });
}

const initTransactions = (id) => {
    let trxColumn = [
        {data: 'id',
            render: function (data, type, row, meta) {
                return meta.row + meta.settings._iDisplayStart + 1;
            },
            width: '5%',
            className: 'text-center'
        },
        {data: 'user', name: 'user'},
        {data: 'trx', name: 'trx'},
        {data: 'description', name: 'description'},
        {data: 'amount', name: 'amount'},
        {data: 'post_balance', name: 'post_balance'},
    ]
    dtTrx = $('#table-transactions').DataTable({
        processing: true,
        serverSide: true,
        responsive: true,
        scrollX: true,
        ajax: {
            url: app_url + '/users/ajax-transaction/' + id,
        },
        columns: trxColumn,
        order: [[0, 'desc']],
    });
}

const initLoginHistory = (id) => {
    let trxColumn = [
        {data: 'id',
            render: function (data, type, row, meta) {
                return meta.row + meta.settings._iDisplayStart + 1;
            },
            width: '5%',
            className: 'text-center'
        },
        {data: 'user', name: 'user'},
        {data: 'login_at', name: 'login_at'},
        {data: 'ip_address', name: 'ip'},
        {data: 'location', name: 'location'},
        {data: 'device', name: 'device'},
    ]
    dtTrx = $('#table-user-login-history').DataTable({
        processing: true,
        serverSide: true,
        responsive: true,
        scrollX: true,
        ajax: {
            url: app_url + '/users/ajax-login-history/' + id,
        },
        columns: trxColumn,
        order: [[0, 'desc']],
    });
}

const submitCategory = (type) => {
    let form = $('#form-category').serialize();
    let url = app_url + '/categories';
    if (type == 'update') {
        url = app_url + '/categories/' + $('#form-category #id').val();
    }

    $.ajax({
        type: type == 'store' ? 'POST' : 'PUT',
        url: url,
        data: form,
        cache: false,
        beforeSend: function () {
            toggleLoading(true, i18n.global.saving);
        },
        success: function (res) {
            toggleLoading(false);
            closeGlobalModal();
            handleSuccess(res.message);
            dt.ajax.reload();
        },
        error: function (err) {
            toggleLoading(false);
            handleError(err);
        }
    })
}

const showConfirm = (title, message, btnOk = i18n.global.yes, btnCancel = i18n.global.no, functions, param = null) => {
    Confirm.show(
        title,
        message,
        btnOk,
        btnCancel,
        () => {
            window[functions](param);
        },
        () => {
            dt.ajax.reload();
        }
    )
}

const updateStatus = (param) => {
    $.ajax({
        type: 'GET',
        url: app_url + '/categories/change-status/' + param.id + '/' + param.status,
        beforeSend: function () {
            toggleLoading(true, i18n.global.saving);
        },
        success: function (res) {
            toggleLoading(false);
            handleSuccess(res.message);
            dt.ajax.reload();
        },
        error: function (err) {
            toggleLoading(false);
            handleError(err);
        }
    })
}

const deleteItem = (param) => {
    $.ajax({
        type: 'DELETE',
        url: app_url + '/categories/' + param.id,
        beforeSend: function () {
            toggleLoading(true, i18n.global.processing);
        },
        success: function (res) {
            toggleLoading(false);
            handleSuccess(res.message);
            dt.ajax.reload();
        },
        error: function (err) {
            toggleLoading(false);
            handleError(err);
        }
    })
}

const submitUser = (id) => {
    var form = $('#form-edit-user').serialize();

    $.ajax({
        type: 'PUT',
        url: app_url + '/users/' + id,
        data: form,
        cache: false,
        beforeSend: function () {
            toggleLoading(true, i18n.global.saving);
            removeValidation('form-edit-user');
        },
        success: function (res) {
            toggleLoading(false);
            handleSuccess(res.message);
            responseUrl(res.data.view, 800);
        },
        error: function (err) {
            toggleLoading(false);
            handleError(err);
        }
    })
}

const submitBalance = (id) => {
    $.ajax({
        type: 'POST',
        url: app_url + '/users/balance/' + id,
        data: $('#form-balance').serialize(),
        beforeSend: function () {
            removeValidation('form-balance');
            toggleLoading(true, i18n.global.processing);
        },
        success: function (res) {
            toggleLoading(false);
            handleSuccess(res.message);
            closeGlobalModal();
            updateBalance(id);
        },
        error: function (err) {
            toggleLoading(false);
            handleError(err);
        }
    })
}

const updateBalance = (id = 0) => {
    $.ajax({
        type: 'GET',
        url: app_url + '/users/update-balance/' + id,
        beforeSend: function () {
            addSpinnerText('heading-balance');
        },
        success: function (res) {
            $('#heading-balance').html(res.data);
        },
        error: function (err) {}
    })
}

const detailTransaction = (id) => {
    responseUrl(app_url + '/users/transactions/' + id);
}

const banUser = (id) => {
    let form = $('#form-ban').serialize();
    $.ajax({
        type: 'POST',
        url: app_url + '/users/do-ban/' + id,
        data: form,
        beforeSend: function () {
            removeValidation('form-ban');
            toggleLoading(true, i18n.global.processing);
        },
        success: function (res) {
            toggleLoading(false);
            handleSuccess(res.message);
            closeGlobalModal();
            responseUrl(app_url + '/users/show/' + res.data.id);
        },
        error: function (err) {
            toggleLoading(false);
            handleError(err);
        }
    })
}

$('#country').select2({
    selectionCssClass: 'form-control',
    placeholder: i18n.global.search + ' ' + i18n.global.country,
    allowClear: true,
    ajax: {
        url: api_url + '/countries',
        type: 'GET',
        data: function (params) {
            var query = {
                name: params.term,
            };

            return query;
        },
        processResults: function (data) {
            var res = [];
            for (let a = 0; a < data.data.length; a++) {
                res.push({
                    id: data.data[a].id,
                    text: data.data[a].name,
                });
            }

            return {
                results: res,
            };
        }
    }
})

$('#state').select2({
    selectionCssClass: 'form-control',
    placeholder: i18n.global.search + ' ' + i18n.global.state,
    allowClear: true,
    ajax: {
        url: api_url + '/provinces',
        type: 'GET',
        data: function (params) {
            var query = {
                name: params.term,
                country_id: $('#country').val()
            };

            return query;
        },
        processResults: function (data) {
            var res = [];
            for (let a = 0; a < data.data.length; a++) {
                res.push({
                    id: data.data[a].id,
                    text: data.data[a].name,
                });
            }

            return {
                results: res,
            };
        }
    }
});

$('#city').select2({
    selectionCssClass: 'form-control',
    placeholder: i18n.global.search + ' ' + i18n.global.city,
    allowClear: true,
    ajax: {
        url: api_url + '/regencies',
        type: 'GET',
        data: function (params) {
            var query = {
                name: params.term,
                province_id: $('#state').val()
            };

            return query;
        },
        processResults: function (data) {
            var res = [];
            for (let a = 0; a < data.data.length; a++) {
                res.push({
                    id: data.data[a].id,
                    text: data.data[a].name,
                });
            }

            return {
                results: res,
            };
        }
    }
});

$('.max-6').attr('maxlength', 6);

window.dt = dt;
window.init = init;
window.submitCategory = submitCategory;
window.showConfirm = showConfirm;
window.updateStatus = updateStatus;
window.deleteItem = deleteItem;
window.submitUser = submitUser;
window.updateBalance = updateBalance;
window.submitBalance = submitBalance;
window.detailTransaction = detailTransaction;
window.initTransactions = initTransactions;
window.initLoginHistory = initLoginHistory;
window.banUser = banUser;
