@extends('layouts.master')

@section('content')
    <div class="card">
        <div class="card-body">
            <form id="form-deposit">
                <div class="form-group mb-4">
                    <label for="payment" class="form-label required">@lang('global.payment_method')</label>
                    <select name="payment"
                        id="payment"
                        class="form-control"
                        onchange="updateDepositRule(this)">
                        <option value=""></option>
                        @foreach ($payments as $item)
                            <option value="{{ $item->detail_id }}">{{ $item->name }}</option>
                        @endforeach
                    </select>
                    <div class="invalid-feedback" id="error-payment"></div>
                </div> <!-- End Form Group -->

                <div class="form-group mb-4 form-group-channel d-none">
                    <label for="channel" class="form-label">Channel</label>
                    <select name="channel"
                        id="channel"
                        class="form-control">
                        <option value=""></option>
                    </select>
                </div>

                <div class="form-group mb-4">
                    <label for="amount" class="form-label required">@lang('global.amount')</label>
                    <x-form.text
                        id="amount"
                        className="onlyNumber"
                        name="amount"
                        error="amount"
                        text="0"></x-form.text>
                    <input type="hidden" name="payable" id="payable">
                </div> <!-- End Form Group -->

                <div class="deposit-rule d-none">
                    <div class="border-bottom py-2 d-flex align-items-center justify-content-between">
                        <p class="mb-0">@lang('global.limit')</p>
                        <p>
                            <span class="fw-bolder" id="target-minimum">100000</span> IDR -
                            <span class="fw-bolder" id="target-maximum">5000000</span> IDR
                        </p>
                        <input type="hidden" id="minimum_value" name="minimum_value">
                        <input type="hidden" id="maximum_value" name="maximum_value">
                    </div>
                    <div class="border-bottom py-2 d-flex align-items-center justify-content-between">
                        <p class="mb-0">@lang('global.charge')</p>
                        <p>
                            <span class="fw-bolder" id="target-charge">0</span> IDR
                            <input type="hidden"
                                name="charge_total"
                                id="charge_total">
                            <input type="hidden"
                                name="fixed_charge"
                                id="target-fixed-charge-value">
                            <input type="hidden"
                                name="percent_charge"
                                id="target-percent-charge-value">
                        </p>
                    </div>
                    <div class="border-bottom py-2 d-flex align-items-center justify-content-between">
                        <p class="mb-0">@lang('global.payable')</p>
                        <p>
                            <span class="fw-bolder" id="target-payable">0</span> IDR
                            <input type="hidden"
                                id="target-payable-value">
                        </p>
                    </div>
                    <div id="target-conversion-rate" class="d-none">
                        <div class="border-bottom py-2 d-flex align-items-center justify-content-between">
                            <p class="mb-0 fw-bolder">@lang('global.conversion_rate')</p>
                            <p>
                                <span class="fw-bolder">1</span> <span class="fw-bolder">IDR =</span> <span class="fw-bolder" id="target-rate"></span> <span class="fw-bolder" id="target-currency"></span>
                            </p>
                        </div>
                        <div class="border-bottom py-2 d-flex align-items-center justify-content-between">
                            <p class="mb-0" id="target-label-currency"></p>
                            <p>
                                <span class="fw-bolder" id="target-rate-result"></span>
                            </p>
                        </div>
                    </div>
                </div>

                <div class="form-group mb-4">
                    <button class="btn btn-primary w-100"
                        type="button"
                        onclick="submitDeposit()">
                        @lang('global.submit')
                    </button>
                </div> <!-- End Form Group -->
            </form> <!-- End Form -->
        </div> <!-- End Card Body -->
    </div> <!-- End Card -->
@endsection

@push('scripts')
    <script src="{{ asset('plugins/custom/numeral/min/numeral.min.js') }}"></script>
    <script src="{{ mix('js/regex.js') }}"></script>
    <script src="{{ mix('js/deposit.js') }}"></script>
    <script>
        $('#payment').select2({
            placeholder: i18n.global.select_payment_method,
        });
        $('#form-deposit #amount').on('input', (e) => {
            // console.log('e',e);
            // var val = parseFloat(e.target.value);
            // var fixedCharge = parseFloat($('.deposit-rule #target-fixed-charge-value').val());
            // var percentCharge = parseFloat($('.deposit-rule #target-percent-charge-value').val());
            // var charge = (parseFloat((val * percentCharge / 100)) + fixedCharge);
            // var payable = val + parseFloat(charge);
            // $('.deposit-rule #target-payable').html(numeral(payable).format('0,0'));
            // $('.deposit-rule #target-charge').html(numeral(charge).format('0,0'));
            // console.log('charge',charge);
            // console.log('val',val);
            // console.log('fixedCharge',fixedCharge);
            // console.log('percentCharge',percentCharge);
            updatePayableAndCharge();
        });
    </script>
@endpush
