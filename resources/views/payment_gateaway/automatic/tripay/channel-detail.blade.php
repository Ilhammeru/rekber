{{-- <link rel="stylesheet" href="{{ asset('plugins/custom/bootstrap-toggle-master/css/bootstrap-toggle.min.css') }}"> --}}

@if (session()->get('failed_generate_tripay_channel'))
    <div class="alert alert-danger">
        <p class="mb-0">{{ session()->get('failed_generate_tripay_channel') }}</p>
    </div>
@endif

@if (count($channels) == 0)
    <div class="text-center">
        <img src="{{ asset('images/distribution.png') }}"
            alt=""
            style="width: 60px; height: 60px;">

        <p class="mt-5">@lang('global.dont_have_channel')</p>
        <button class="btn btn-primary"
            type="button"
            onclick="generateTripayChannel('{{ $id }}')">
            @lang('global.generate_channel_from_tripay')
        </button>
    </div>
@else
    <div class="row">
        @foreach ($channels as $kc => $channel)
        <div class="col-md-6">
            <div class="d-flex align-items-start gap-5 mb-5">
                <div class="d-flex align-items-center justify-content-start">
                    <img src="{{ $channel['icon_url'] }}" style="height: auto; width: 100px;" alt="">
                </div>
                <div>
                    <div class="row">
                        <label for="status" class="col-form-label col-md-4">@lang('global.status')</label>
                        <div class="col-md-8 pt-4">
                            <div class="form-check form-switch">
                                @php
                                    $checked = '';
                                    if (isset($channel['status'])) {
                                        if ($channel['status']) {
                                            $checked = 'checked';
                                        }
                                    }
                                @endphp
                                <input class="form-check-input"
                                    type="checkbox"
                                    role="switch"
                                    id="status_channel_{{ $kc }}"
                                    {{ $checked }}
                                    name="channels[{{$kc}}][status]">
                                <input type="hidden"
                                    name="channels[{{$kc}}][code]"
                                    value="{{ $channel['code'] }}">
                                <label class="form-check-label" for="status_channel_{{ $kc }}"></label>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <label for="name" class="col-form-label col-md-4">@lang('global.name')</label>
                        <div class="col-md-8">
                            <input type="text"
                                class="form-control ps-0 border-0"
                                value="{{ $channel['name'] }}"
                                readonly>
                        </div>
                    </div>
                    <div class="row">
                        <label for="type" class="col-form-label col-md-4">@lang('global.type')</label>
                        <div class="col-md-8">
                            <input type="text"
                                class="form-control ps-0 border-0"
                                value="{{ $channel['type'] }}"
                                readonly>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <p class="mb-2 fw-bold text-center">@lang('global.fee_merchant')</p>
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th class="border border-secondary text-center">@lang('global.fix_charge')</th>
                                        <th class="border border-secondary text-center">@lang('global.percent_charge')</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td class="border border-secondary text-center">{{ number_format($channel['fee_merchant']['flat']) }}</td>
                                        <td class="border border-secondary text-center">{{ number_format($channel['fee_merchant']['percent']) }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    </div>

    {{-- <div class="text-center mt-5">
        <button class="btn btn-primary"
            type="button"
            onclick="generateTripayChannel('{{ $id }}')">
            @lang('global.generate_channel_from_tripay')
        </button>
    </div> --}}
@endif

{{-- <script src="{{ asset('plugins/custom/bootstrap-toggle-master/js/bootstrap-toggle.min.js') }}"></script> --}}
