import tippy from 'tippy.js';
import 'tippy.js/dist/tippy.css';
import 'tippy.js/themes/light.css';
import 'tippy.js/animations/scale.css';
import { Confirm } from '../../public/plugins/custom/notiflix/build/notiflix-confirm-aio';

var dt;
var columns = [
    {data: 'id',
        render: function (data, type, row, meta) {
            return meta.row + meta.settings._iDisplayStart + 1;
        },
        width: '5%',
        className: 'text-center'
    },
    {data: 'name', name: 'name'},
    {data: 'status', name: 'status'},
    {data: 'created_at', name: 'created_at', visible: false},
    {data: 'action', name: 'action', className: 'text-center', orderable: false},
];

const init = (tableId) => {
    dt = $('#' + tableId).DataTable({
        processing: true,
        serverSide: true,
        responsive: true,
        scrollX: true,
        ajax: {
            url: app_url + '/categories/ajax',
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

window.dt = dt;
window.init = init;
window.submitCategory = submitCategory;
window.showConfirm = showConfirm;
window.updateStatus = updateStatus;
window.deleteItem = deleteItem;

init('table-category');
