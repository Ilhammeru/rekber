@extends('layouts.master')

@push('styles')
    <link href="//cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">
    <link href="//cdn.quilljs.com/1.3.6/quill.bubble.css" rel="stylesheet">
    <style>
        .card-custom-payment {
            min-height: auto !important;
        }
        .ql-editor {
            height: 200px;
        }
    </style>
@endpush

@section('content')

<div class="card card-body mb-5">
    <div class="text-start">
        <a class="btn btn-sm btn-primary"
            href="{{ route('payment-gateaway.index', $type) }}">@lang('global.back')</a>
    </div>
</div>

<!--begin::Card-->
<div class="card">

    <!-- Main Card -->
    <div class="card-body">

        <form id="form-manual-payment">
            <div class="row mb-5">

                <div class="col-md-4 col-sm-12 col-xl-4">

                    <div class="form-group">
                        <label for="name" class="form-label required">@lang('global.name')</label>
                        <x-form.text
                            id="name"
                            error="name"
                            name="name"
                            text="name"
                            value="{{ $data ? $data->name : '' }}"></x-form.text>
                    </div><!-- End Form Group -->

                </div> <!-- End Col -->

                <div class="col-md-4 col-sm-12 col-xl-4">

                    <div class="form-group">
                        <label for="currency" class="form-label required">@lang('global.currency')</label>
                        <x-form.text
                            id="currency"
                            error="currency"
                            name="currency"
                            text="currency"
                            oninput="updateCurrency(this)"
                            value="{{ $data ? $data->detail->currency : '' }}"></x-form.text>
                    </div><!-- End Form Group -->

                </div> <!-- End Col -->

                <div class="col-md-4 col-sm-12 col-xl-4">

                    <div class="form-group">
                        <label for="currency" class="form-label required">@lang('global.currency')</label>
                        <div class="input-group mb-3">
                            <span class="input-group-text">1 IDR =</span>
                            <input type="text"
                                class="form-control onlyNumber"
                                id="rate"
                                name="rate"
                                value="{{ $data ? $data->detail->rate : '' }}"
                                aria-label="Amount (to the nearest dollar)">
                            <span class="input-group-text" id="target-rate-currency">{{ $data ? $data->detail->currency : '' }}</span>
                        </div>
                        <div class="invalid-feedback" id="error-rate"></div>
                    </div><!-- End Form Group -->

                </div> <!-- End Col -->

            </div><!-- End Row -->

            <div class="row mb-5">

                <div class="col-md-6 col-xl-6 col-sm-12">

                    <div class="card">
                        <div class="card-header bg-primary card-custom-payment py-3">
                            <h5>@lang('global.range')</h5>
                        </div>
                        <div class="card-body border border-primary">

                            <div class="form-group mb-3">
                                <label for="minimum" class="form-label required">
                                    @lang('global.minimum') @lang('global.amount')
                                </label>
                                <div class="input-group mb-3">
                                    <div class="input-group">
                                        <input type="text"
                                            class="form-control onlyNumber"
                                            id="minimum"
                                            name="minimum"
                                            value="{{ $data ? $data->detail->minimum_trx : 0 }}"
                                            aria-label="Amount (to the nearest dollar)">
                                        <span class="input-group-text">{{ $data ? $data->detail->currency : '' }}</span>
                                    </div>
                                </div>
                                <div class="invalid-feedback" id="error-minimum"></div>
                            </div>

                            <div class="form-group mb-3">
                                <label for="maximum" class="form-label required">
                                    @lang('global.maximum') @lang('global.amount')
                                </label>
                                <div class="input-group mb-3">
                                    <div class="input-group">
                                        <input type="text"
                                            class="form-control onlyNumber"
                                            id="maximum"
                                            name="maximum"
                                            value="{{ $data ? $data->detail->maximum_trx : 0 }}"
                                            aria-label="Amount (to the nearest dollar)">
                                        <span class="input-group-text">{{ $data ? $data->detail->currency : '' }}</span>
                                    </div>
                                </div>
                                <div class="invalid-feedback" id="error-maximum"></div>
                            </div>

                        </div> <!-- Card body -->
                    </div>

                </div><!-- End Col -->

                <div class="col-md-6 col-xl-6 col-sm-12">

                    <div class="card">
                        <div class="card-header bg-primary card-custom-payment py-3">
                            <h5>@lang('global.charge')</h5>
                        </div>
                        <div class="card-body border border-primary">

                            <div class="form-group mb-3">
                                <label for="fix_charge" class="form-label required">
                                    @lang('global.fix_charge')
                                </label>
                                <div class="input-group mb-3">
                                    <div class="input-group">
                                        <input type="text"
                                            class="form-control onlyNumber"
                                            id="fix_charge"
                                            name="fix_charge"
                                            value="{{ $data ? $data->detail->fixed_charge : 0 }}"
                                            aria-label="Amount (to the nearest dollar)">
                                        <span class="input-group-text">{{ $data ? $data->detail->currency : '' }}</span>
                                    </div>
                                </div>
                                <div class="invalid-feedback" id="error-fix_charge"></div>
                            </div>

                            <div class="form-group mb-3">
                                <label for="maximum" class="form-label required">
                                    @lang('global.percent_charge')
                                </label>
                                <div class="input-group mb-3">
                                    <div class="input-group">
                                        <input type="text"
                                            class="form-control onlyNumber"
                                            id="percent_charge"
                                            name="percent_charge"
                                            value="{{ $data ? $data->detail->percent_charge : 0 }}"
                                            aria-label="Amount (to the nearest dollar)">
                                        <span class="input-group-text">%</span>
                                    </div>
                                </div>
                                <div class="invalid-feedback" id="error-percent_charge"></div>
                            </div>

                        </div> <!-- End Card body -->

                    </div> <!-- End Card -->

                </div><!-- End Col -->

            </div><!-- End Row -->

            @include('payment_gateaway.partials.description')
            @include('payment_gateaway.partials.user_data')

            <div class="row mt-4">
                <div class="col-12">
                    <button class="btn btn-success w-100"
                        id="btn-submit-payment"
                        type="button"
                        onclick="saveManualPayment('{{ $id }}')">
                        @lang('global.submit')
                    </button>
                </div>
            </div>

        </form> <!-- End Form -->

    </div> <!-- end Main Card Body -->

</div>
<!--end::End Main Card-->

@endsection

@push('scripts')
<script src="https://cdn.quilljs.com/1.3.6/quill.js"></script>
<script src="{{ mix('js/regex.js') }}"></script>
<script src="{{ mix('js/payment_gateaway.js') }}"></script>
<script>
    $(document).ready(function () {
        initQuillInstruction();
        initUserData('{{ $id }}')
    })
</script>
@endpush
