var dt;

const init = () => {
    dt = $('#table-deposit').DataTable({
        processing: true,
        serverSide: true,
        responsive: true,
        scrollX: true,
        ajax: {
            url: app_url + '/deposit/ajax/' + status,
        },
        columns: [
            {data: 'id',
                render: function (data, type, row, meta) {
                    return meta.row + meta.settings._iDisplayStart + 1;
                },
                width: '5%',
                className: 'text-center'
            },
            {data: 'payment_gateaway', name: 'payment_gateaway'},
            {data: 'amount', name: 'amount'},
            {data: 'status', name: 'status'},
            {data: 'action', name: 'action', orderable: false},
        ],
        order: [[0, 'desc']],
    });
}

const updateDepositRule = (e) => {
    var id = e.value;
    $.ajax({
        type: 'GET',
        url: app_url + '/deposit/update-deposit-rule/' + id,
        beforeSend: function () {
            $('#channel').html('');
            $('.form-group-channel').addClass('d-none');
        },
        success: function (res) {
            console.log('res',res);
            $('.deposit-rule #minimum_value').val(res.data.detail.minimum_trx);
            $('.deposit-rule #maximum_value').val(res.data.detail.maximum_trx);
            $('.deposit-rule #target-minimum').html(numeral(parseFloat(res.data.detail.minimum_trx)).format('0,0'));
            $('.deposit-rule #target-maximum').html(numeral(parseFloat(res.data.detail.maximum_trx)).format('0,0'));
            $('.deposit-rule #target-fixed-charge-value').val(res.data.detail.fixed_charge);
            $('.deposit-rule #target-percent-charge-value').val(res.data.detail.percent_charge);
            if (res.data.need_to_rate) {
                $('#target-conversion-rate #target-rate').html(res.data.detail.rate);
                $('#target-conversion-rate #target-label-currency').html(i18n.global.in_current + ' ' + res.data.detail_currency);
                $('#target-conversion-rate #target-rate-result').html(0);
                $('#target-conversion-rate').removeClass('d-none');
            } else {
                $('#target-conversion-rate').addClass('d-none');
            }

            // update channel

            var optChannel = '';
            if (res.data.channel.length) {
                $('.form-group-channel').removeClass('d-none');
                optChannel = '<option></option>';
                for (let a = 0; a < res.data.channel.length; a++) {
                    optChannel += `<option value="${res.data.channel[a].code}">${res.data.channel[a].name}</option>`;
                }
                $('#channel').html(optChannel);
                $('#channel').select2({
                    placeholder: 'Channel',
                });
            }

            $('.deposit-rule').removeClass('d-none');

            updatePayableAndCharge();
        },
        error: function (err) {
            handleError(err);
        }
    })
}

const updatePayableAndCharge = () => {
    var amount = $('#form-deposit #amount').val();
    var payment = $('#form-deposit #payment').val();
    var fixedCharge = parseFloat($('.deposit-rule #target-fixed-charge-value').val());
    var percentCharge = parseFloat($('.deposit-rule #target-percent-charge-value').val());

    if (payment && amount) {
        amount = parseFloat(amount);
        var charge = (parseFloat((amount * percentCharge / 100)) + fixedCharge);
        var payable = amount + parseFloat(charge);
        var currentRate = $('#target-conversion-rate #target-rate').html();
        if (currentRate) {
            var rateResult = parseFloat(currentRate) * parseFloat(payable);
            $('#target-conversion-rate #target-rate-result').html(rateResult);
        }
        $('.deposit-rule #target-payable').html(numeral(payable).format('0,0'));
        $('.deposit-rule #target-charge').html(numeral(charge).format('0,0'));
        $('.deposit-rule #charge_total').val(charge);
        $('#form-deposit #payable').val(payable);
    }
}

const submitDeposit = () => {
    let form = $('#form-deposit').serializeArray();

    $.ajax({
        type: 'POST',
        url: app_url + '/deposit',
        data: form,
        beforeSend: function () {
            toggleLoading(true, i18n.global.processing);
            removeValidation('form-deposit');
        },
        success: function (res) {
            console.log('res',res);
            var url;
            if (res.data.url) {
                url = res.data.url;
            } else if (res.data.return_url) {
                url = res.data.return_url;
            }

            toggleLoading(false);
            handleSuccess(res.message);
            responseUrl(url, 1200);
        },
        error: function (err) {
            toggleLoading(false);
            handleError(err);
        }
    })
}

const sendPaymentProof = (trx) => {
    var formData = new FormData($("#form-confirm-payment")[0]);

    $.ajax({
        type: 'POST',
        url: app_url + '/deposit/confirm-payment/' + trx,
        data: formData,
        async: false,
        cache: false,
        contentType: false,
        enctype: 'multipart/form-data',
        processData: false,
        beforeSend: function () {
            toggleLoading(true, i18n.global.processing);
            removeValidation('form-confirm-payment');
        },
        success: function (res) {
            toggleLoading(false);
            console.log('res',res);
            handleSuccess(res.message);
            responseUrl(res.data.url, 1200);
        },
        error: function (err) {
            toggleLoading(false);
            handleError(err);
        }
    })
}

const confirmDeposit = (trxId) => {
    Confirm.show(
        i18n.global.confirm + ' ' + i18n.global.deposit,
        i18n.global.confirm_deposit_text,
        i18n.global.yes,
        i18n.global.no,
        () => {
            doConfirmDeposit(trxId)
        }
    );
}

const doConfirmDeposit = (trxId) => {
    $.ajax({
        type: 'GET',
        url: app_url + '/deposit/do-confirm/' + trxId,
        beforeSend: function () {
            toggleLoading(true, i18n.global.processing);
        },
        success: function (res) {
            toggleLoading(false);
            handleSuccess(res.message);
            responseUrl(app_url + '/dashboard', 1200);
        },
        error: function (err) {
            toggleLoading(false);
            handleError(err);
        }
    })
}

const confirmApproveDeposit = (trx) => {
    Confirm.show(
        i18n.global.are_you_sure,
        i18n.global.confirm_approve_text,
        i18n.global.yes,
        i18n.global.no,
        () => {
            doConfirmDeposit(trx);
        }
    )
}

const submitReason = (trx) => {
    let form = $('#form-decline').serializeArray();

    $.ajax({
        type: 'POST',
        url: app_url + '/deposit/decline/' + trx,
        data: form,
        beforeSend: function () {
            toggleLoading(true, i18n.global.processing);
            removeValidation('form-decline');
        },
        success: function (res) {
            toggleLoading(false);
            handleSuccess(res.message);
            responseUrl(res.data.url, 1200);
        },
        error: function (err) {
            toggleLoading(false);
            handleError(err);
        }
    });
}

window.updateDepositRule = updateDepositRule;
window.updatePayableAndCharge = updatePayableAndCharge;
window.submitDeposit = submitDeposit;
window.sendPaymentProof = sendPaymentProof;
window.init = init;
window.confirmDeposit = confirmDeposit;
window.doConfirmDeposit = doConfirmDeposit;
window.confirmApproveDeposit = confirmApproveDeposit;
window.submitReason = submitReason;

init();
