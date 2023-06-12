@php
    $nextKey = count($details);
@endphp

<div id="row-currency-{{ $nextKey }}" class="row-currency">
    <div class="d-flex align-items-center justify-content-between pb-3">
        <h4>{{ $data->name }} {{ $currency }}</h4>
        <button class="btn btn-danger"
            id="btn-remove"
            onclick="deleteRowCurrency({{ $nextKey }})"
            type="button">
            <i class="fa fa-trash"></i>
            @lang('global.delete')
        </button>
    </div>

    <div class="form-group mb-4">
        <input type="text"
            name="details[{{ $nextKey }}][name]"
            id="details.{{ $nextKey }}.name"
            class="form-control">
        <div class="invalid-feedback" id="error-details.{{ $nextKey }}.name"></div>
        <input type="hidden" class="selected_currency" value="{{ $currency }}">
    </div>

    <div class="row mb-4">
        <div class="col-md-4 col-sm-12 mb-5">
            <div class="card">
                <div class="card-header align-items-center bg-primary card-custom-payment py-3">
                    <h5>@lang('global.range')</h5>
                </div>
                <div class="card-body border border-primary">

                    <div class="form-group mb-4">
                        <label for="minimum" class="form-label required">@lang('global.minimum') @lang('global.amount')</label>
                        <div class="input-group">
                            <input type="text"
                                class="form-control onlyNumber"
                                id="minimum"
                                name="details[{{ $nextKey }}][minimum]">
                            <span class="input-group-text" id="basic-addon2">IDR</span>
                        </div>
                        <div class="invalid-feedback" id="error-minimum"></div>
                    </div> <!-- End Form Group -->

                    <div class="form-group mb-4">
                        <label for="maximum" class="form-label required">@lang('global.maximum') @lang('global.amount')</label>
                        <div class="input-group">
                            <input type="text"
                                class="form-control onlyNumber"
                                id="maximum"
                                name="details[{{ $nextKey }}][maximum]">
                            <span class="input-group-text" id="basic-addon2">IDR</span>
                        </div>
                        <div class="invalid-feedback" id="error-maximum"></div>
                    </div> <!-- End Form Group -->

                </div> <!-- End Card body -->
            </div> <!-- End Card -->
        </div><!-- End Col -->

        <div class="col-md-4 col-sm-12 mb-5">
            <div class="card">
                <div class="card-header align-items-center bg-primary card-custom-payment py-3">
                    <h5>@lang('global.charge')</h5>
                </div>
                <div class="card-body border border-primary">

                    <div class="form-group mb-4">
                        <label for="fix_charge" class="form-label required">@lang('global.fix_charge')</label>
                        <div class="input-group">
                            <input type="text"
                                class="form-control onlyNumber"
                                id="fix_charge"
                                name="details[{{ $nextKey }}][fix_charge]">
                            <span class="input-group-text" id="basic-addon2">IDR</span>
                        </div>
                        <div class="invalid-feedback" id="error-fix_charge"></div>
                    </div> <!-- End Form Group -->

                    <div class="form-group mb-4">
                        <label for="percent_charge" class="form-label required">@lang('global.percent_charge')</label>
                        <div class="input-group">
                            <input type="text"
                                class="form-control onlyNumber"
                                id="percent_charge"
                                name="details[{{ $nextKey }}][percent_charge]">
                            <span class="input-group-text" id="basic-addon2">IDR</span>
                        </div>
                        <div class="invalid-feedback" id="error-percent_charge"></div>
                    </div> <!-- End Form Group -->

                </div> <!-- End Card body -->
            </div> <!-- End Card -->
        </div><!-- End Col -->

        <div class="col-md-4 col-sm-12 mb-5">
            <div class="card">
                <div class="card-header align-items-center bg-primary card-custom-payment py-3">
                    <h5>@lang('global.currency')</h5>
                </div>
                <div class="card-body border border-primary">

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group mb-4">
                                <label for="currency" class="form-label required">@lang('global.currency')</label>
                                <input type="text"
                                    class="form-control onlyNumber bg-light"
                                    name="details[{{ $nextKey }}][currency]"
                                    id="currency"
                                    value="{{ $currency }}"
                                    readonly>
                                <div class="invalid-feedback" id="error-currency"></div>
                            </div> <!-- End Form Group -->
                        </div>
                        <div class="col-md-6">
                            <div class="form-group mb-4">
                                <label for="symbol" class="form-label required">@lang('global.symbol')</label>
                                <input type="text"
                                    class="form-control"
                                    id="symbol"
                                    name="details[{{ $nextKey }}][symbol]">
                                <div class="invalid-feedback" id="error-symbol"></div>
                            </div> <!-- End Form Group -->
                        </div>
                        <div class="col-md-12">
                            <div class="form-group mb-4">
                                <label for="rate" class="form-label required">@lang('global.rate')</label>
                                <div class="input-group">
                                    <span class="input-group-text" id="basic-addon2">1 IDR =</span>
                                    <input type="text"
                                        class="form-control onlyNumber"
                                        id="rate"
                                        name="details[{{ $nextKey }}][rate]">
                                    <span class="input-group-text" id="basic-addon2">{{ $currency }}</span>
                                </div>
                                <div class="invalid-feedback" id="error-rate"></div>
                            </div> <!-- End Form Group -->
                        </div>
                    </div>


                </div> <!-- End Card body -->
            </div> <!-- End Card -->
        </div><!-- End Col -->

    </div> <!-- End Row -->
</div>
