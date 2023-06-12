var dt;
var InsQuill;
var all_currency = [
    'IDR',
    'USD',
    'INR',
    'JPY'
];

const init = (type) => {
    if (type == 'automatic') return initAutomatic(type);

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
        {data: 'action', name: 'action', className: 'text-end'},
    ];

    dt = $('#table-payment-gateaway').DataTable({
        processing: true,
        serverSide: true,
        responsive: true,
        scrollX: true,
        ajax: app_url + '/payment-gateaway/ajax/' + type,
        columns: columns,
        ordering: false,
        order: [[3, 'desc']],
    });
}

const initAutomatic = (type) => {
    var columns = [
        {data: 'id',
            render: function (data, type, row, meta) {
                return meta.row + meta.settings._iDisplayStart + 1;
            },
            width: '5%',
            className: 'text-center'
        },
        {data: 'name', name: 'name'},
        {data: 'enable_currency', name: 'enable_currency'},
        {data: 'status', name: 'status'},
        {data: 'action', name: 'action', className: 'text-end'},
    ];

    dt = $('#table-auto-payment-gateaway').DataTable({
        processing: true,
        serverSide: true,
        responsive: true,
        scrollX: true,
        ajax: app_url + '/payment-gateaway/automatic/ajax',
        columns: columns,
        ordering: false,
        order: [[0, 'desc']],
    });
}

const updateCurrency = (e) => {
    $('#target-rate-currency').text(e.value);
}

const initQuillInstruction = () => {
    var dataVal = $('#instruction').attr('data-value');

    var options = {
        placeholder: 'Compose an epic...',
        theme: 'snow',
        height: '500px',
    };
    InsQuill = new Quill('#instruction', options);

    if (dataVal) {
        InsQuill.root.innerHTML = dataVal;
    }
}

const submitUserData = (id) => {
    let form = $('#form-user-data').serialize();

    $.ajax({
        type: 'POST',
        url: app_url + '/payment-gateaway/user-data/' + id,
        data: form,
        beforeSend: function () {
            removeValidation('form-user-data');
            toggleLoading(true, i18n.global.saving);
        },
        success: function (res) {
            closeGlobalModal();
            handleSuccess(res.message);
            toggleLoading(false);
            initUserData(id);
        },
        error: function (err) {
            toggleLoading(false);
            handleError(err, 'form-user-data');
        }
    })
}

const initUserData = (id) => {
    $.ajax({
        type: 'GET',
        url: app_url + '/payment-gateaway/user-data/' + id,
        beforeSend: function () {
            addSpinnerText('target-user-data');
        },
        success: function (res) {
            $('#target-user-data').html(res.data);
        },
        error: function (err) {}
    })
}

const deleteUserData = (key) => {
    $.ajax({
        type: 'GET',
        url: app_url + '/payment-gateaway/user-data/delete/' + key,
        beforeSend: function () {
            toggleLoading(true, i18n.global.processing);
        },
        success: function (res) {
            toggleLoading(false);
            handleSuccess(res.message);
            $('#target-user-data').html(res.data);
        },
        error: function (err) {
            toggleLoading(false);
            handleError(err);
        }
    })
}

const saveManualPayment = (id = null) => {
    let form = $("#form-manual-payment").serializeArray();
    form.push({name: 'instruction', value: InsQuill.getText()});
    form.push({name: 'instruction_real', value: InsQuill.root.innerHTML});
    if (id) {
        form.push({name: 'current_id', value: id});
    }

    $.ajax({
        type: 'POST',
        url: app_url + '/payment-gateaway/store/data',
        data: form,
        beforeSend: function () {
            removeValidation('form-manual-payment');
            toggleLoading(true, i18n.global.saving);
        },
        success: function (res) {
            toggleLoading(false);
            handleSuccess(res.message);
            responseUrl(res.data.url, 1000);
        },
        error: function (err) {
            toggleLoading(false);
            handleError(err);
        }
    })
}

const confirmDelete = (id) => {
    Confirm.show(
        i18n.global.delete_confirmation_title,
        i18n.global.delete_confirmation,
        i18n.yes,
        i18n.no,
        () => {
            deleteItem(id);
        }
    )
}

const deleteItem = (id) => {
    $.ajax({
        type: "DELETE",
        url: app_url + '/payment-gateaway/delete/' + id,
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
    });
}

const updateAvailableCurrency = (id) => {
    // $.ajax({
    //     type: 'GET',
    //     url: app_url + '/payment-gateaway/automatic/update-currency/' + id,
    //     beforeSend: function () {},
    //     success: function (res) {
    //         let data = res.data;
    //         let opt = `<option value="" selected disabled>${i18n.global.select_currency}</option>`;
    //         for (let a = 0; a < data.length; a++) {
    //             opt += `<option value="${data[a]}">${data[a]}</option>`;
    //         }
    //         $('#select_currency').html(opt);
    //     },
    //     error: function (err) {}
    // })
    var all = $('.selected_currency');
    for (let a = 0; a < all.length; a++) {
        var val = all[a].value;
        var index = all_currency.indexOf(val);
        if (index > -1) {
            all_currency.splice(index, 1);
        }
    }

    let opt = `<option value="" selected disabled>${i18n.global.select_currency}</option>`;
    for (let a = 0; a < all_currency.length; a++) {
        opt += `<option value="${all_currency[a]}">${all_currency[a]}</option>`;
    }
    $('#select_currency').html(opt);
}

const initDetailCurrency = (id) => {
    $.ajax({
        type: 'GET',
        url: app_url + '/payment-gateaway/automatic/init-detail-currency/' + id,
        beforeSend: function () {},
        success: function (res) {
            $('#target-detail-currency').html(res.data.view);
            console.log('oke',$('#details_0_name'));
            updateAvailableCurrency();
        },
        error: function (err) {
            handleError(err);
        }
    })
}

const saveAutomaticGateaway = (id) => {
    let form = $('#form-automatic-gateaway').serializeArray();

    $.ajax({
        type: "POST",
        url: app_url + '/payment-gateaway/automatic/store/' + id,
        data: form,
        beforeSend: function () {
            toggleLoading(true, i18n.global.saving);
            removeValidation('form-automatic-gateaway');
        },
        success: function (res) {
            toggleLoading(false);
            console.log('res',res);
            handleSuccess(res.message);
            // responseUrl(app_url + '/payment-gateaway/automatic', 1000);
        },
        error: function (err) {
            toggleLoading(false);
            handleError(err);
        }
    })
}

const addNewCurrency = (id) => {
    let currency = $('#select_currency').val();
    if (currency) {
        $.ajax({
            type: 'POST',
            url: app_url + '/payment-gateaway/automatic/add-new-currency-form/' + id,
            data: {
                currency: currency,
            },
            beforeSend: function () {},
            success: function (res) {
                $('#target-detail-currency').append(res.data.view);
                updateAvailableCurrency();
            },
            error: function (err) {
                handleError(err);
            }
        });
    }

}

const deleteRowCurrency = (key) => {
    //validate row
    let deletedCurrency = $(`#row-currency-${key} .selected_currency`).val();
    all_currency.push(deletedCurrency);

    var rows = $('.row-currency');
    if (rows.length > 1) {
        $('#row-currency-' + key).remove();
    }

    // update currency
    updateAvailableCurrency();
}

const generateTripayChannel = (id) => {
    $.ajax({
        type: 'GET',
        url: app_url + '/payment-gateaway/automatic/channel-tripay/' + id,
        beforeSend: function () {
            addSpinnerHuge('target-channel');
        },
        success: function (res) {
            console.log('res',res);
            $('#target-channel').html(res.data.view);
        },
        error: function (err) {
            handleError(err);
        }
    })
}

const initTripayChannel = (id) => {
    $.ajax({
        type: 'GET',
        url: app_url + '/payment-gateaway/automatic/local-channel-tripay/' + id,
        beforeSend: function () {
            addSpinnerHuge('target-channel');
        },
        success: function (res) {
            console.log('res',res);
            $('#target-channel').html(res.data.view);
        },
        error: function (err) {
            $('#target-channel').html('');
            handleError(err);
        }
    })
}

window.dt = dt;
window.all_currency = all_currency;
window.InsQuill = InsQuill
window.init = init;
window.updateCurrency = updateCurrency;
window.initQuillInstruction = initQuillInstruction;
window.submitUserData = submitUserData;
window.initUserData = initUserData;
window.deleteUserData = deleteUserData;
window.saveManualPayment = saveManualPayment;
window.confirmDelete = confirmDelete;
window.deleteItem = deleteItem;
window.initAutomatic = initAutomatic;
window.updateAvailableCurrency = updateAvailableCurrency;
window.initDetailCurrency = initDetailCurrency;
window.saveAutomaticGateaway = saveAutomaticGateaway;
window.addNewCurrency = addNewCurrency;
window.deleteRowCurrency = deleteRowCurrency;
window.generateTripayChannel = generateTripayChannel;
window.initTripayChannel = initTripayChannel;
