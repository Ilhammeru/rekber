@extends('layouts.master')

@push('styles')
    {{-- <link rel="stylesheet" href="{{ asset('plugins/custom/bootstrap-toggle-master/css/bootstrap-toggle.min.css') }}"> --}}
    <style>
        .skeleton-header-detail-currency {
            width: 100%;
            height: 30px;
            border-radius: 8px;
        }
        .skeleton-card-detail-currency {
            width: 100%;
            height: 160px;
            border-radius: 10px;
        }
        .toggle.collapsible.collapsed .toggle-on, .toggle:not(.collapsible):not(.active) .toggle-on {
            display: block;
        }
        .table tbody tr:last-child td, .table tbody tr:last-child th, .table tfoot tr:last-child td, .table tfoot tr:last-child th {
            border-bottom: 1px solid #e4e6ef!important;
        }
    </style>
@endpush

@section('content')
    <div class="card">
        <div class="card-body">
            <div class="auto-header d-flex align-items-center justify-content-between mb-5">
                <h4 class="mb-0">{{ $data->name }}</h4> <!-- End Title -->
                <div class="input-group mb-0" style="width: auto !important;">
                    <select name="select_currency" id="select_currency" class="form-control">
                        <option value="" selected disabled>@lang('global.select_currency')</option>
                    </select>
                    <button class="input-group-text btn btn-primary"
                        id="basic-addon2"
                        type="button"
                        onclick="addNewCurrency('{{ $id }}')">Add New</button>
                </div>
            </div> <!-- End Header -->

            <form id="form-automatic-gateaway">
                <div class="auto-body pt-5">
                    <h5>{{ __('global.global_setting_for', ['name' => $data->name]) }}</h5>

                    <div class="row pt-3">
                        @foreach ($configuration as $kc => $config)
                            <div class="col-md-6">
                                <div class="form-group mb-4">
                                    <label for="" class="form-label">{{ $config['label'] }}</label>
                                    <input type="hidden" name="config[{{ $kc }}][slug]" value="{{ $config['slug'] }}">
                                    @if ($config['type'] == 'text')
                                        <x-form.text
                                            :id="$config['slug']"
                                            name="config[{{ $kc }}][{{ $config['slug'] }}]"
                                            :text="$config['label']"
                                            :value="$config['value']"
                                            :error="$config['slug']"></x-form.text>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div> <!-- End Row -->

                    <div class="divide"></div>

                    @if ($data->is_have_channel)
                        @if ($data->name == 'Tripay')
                            @include('payment_gateaway.automatic.tripay.channels')
                        @endif
                    @endif

                    <div id="target-detail-currency">
                        <div class="skeleton skeleton-header-detail-currency mb-5"></div>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="skeleton-card-detail-currency skeleton mb-5"></div>
                            </div>
                            <div class="col-md-4">
                                <div class="skeleton-card-detail-currency skeleton mb-5"></div>
                            </div>
                            <div class="col-md-4">
                                <div class="skeleton-card-detail-currency skeleton mb-5"></div>
                            </div>
                        </div>
                    </div>
                    <div class="invalid-feedback" id="error-details"></div>

                    <button class="btn btn-success w-100"
                        id="btn-submit"
                        type="button"
                        onclick="saveAutomaticGateaway('{{ $id }}')">
                        @lang('global.save')
                    </button>
                </div> <!-- End Auto Body -->
            </form> <!-- End Form -->

        </div> <!-- End Card Body -->
    </div> <!-- End Card -->
@endsection

@push('scripts')
    {{-- <script src="{{ asset('plugins/custom/bootstrap-toggle-master/js/bootstrap-toggle.min.js') }}"></script> --}}
    <script src="{{ mix('js/payment_gateaway.js') }}"></script>
    <script>
        initDetailCurrency('{{ $id }}');
        initTripayChannel('{{ $id }}');
    </script>
@endpush
