@extends('layouts.master')

@push('styles')
    <style>
        .table-result {
            padding: 25px 200px;
        }
        .button-action {
            padding: 0 200px;
        }
    </style>
@endpush

@section('content')
    <div class="card">
        <div class="card-body">
            <div class="text-center">
                <h5># {{ $data->charge->trx_id }}</h5>
                <p class="mb-0" style="font-size: 11px;">@lang('global.paid_at') <b>{{ date('d F Y H:i:s', strtotime($data->depositManual->created_at)) }}</b></p>
            </div>

            <div class="table-result mt-5">
                @if (count($data->proof_files) > 0)
                    <p>
                        <a data-bs-toggle="collapse" href="#collapseExample" role="button" aria-expanded="false" aria-controls="collapseExample">@lang('global.see_attachment')</a>
                    </p>
                    <div class="collapse" id="collapseExample">
                        <div class="card card-body p-1">
                            <div class="row">
                                @foreach ($data->proof_files as $file)
                                    <div class="col-md-3 col-sm-12">
                                        <img src="{{ asset($file) }}"
                                            style="width: 120px; height: auto; cursor: pointer;"
                                            alt=""
                                            onclick="openGlobalModal('{{ url('deposit/image') . '?img=' . encrypt($file) }}', '{{ __('global.attachment') }}')">
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                @endif
                <table class="table mt-2">
                    <thead>
                        <tr>
                            <th colspan="5" class="border text-center fw-bolder">{{ $data->charge->gateaway->payment->name }}</th>
                        </tr>
                        <tr>
                            <th class="border ps-3">@lang('global.sender') @lang('global.name')</th>
                            <th class="border">@lang('global.fix_charge')</th>
                            <th class="border">@lang('global.percent_charge')</th>
                            <th class="border">@lang('global.request_amount')</th>
                            <th class="border">@lang('global.total_transfer')</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr class="border">
                            <td>
                                <div>
                                    <p class="mb-0 fw-bolder ps-3">{{ $data->user->email }}</p>
                                </div>
                            </td>
                            <td>{{ number_format($data->charge->fixed_charge) }}</td>
                            <td>{{ $data->charge->percent_charge }} %</td>
                            <td class="fw-bolder">{{ number_format((float)$data->depositManual->amount - (float)$data->charge->total_charge) }}</td>
                            <td class="fw-bolder">{{ number_format($data->depositManual->amount) }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="d-flex align-items-center justify-content-between button-action gap-5">
                <button class="btn btn-secondary w-100"
                    type="button"
                    onclick="">@lang('global.decline')</button>
                <button class="btn btn-primary w-100"
                    type="button"
                    onclick="doConfirmDeposit('{{ encrypt($data->charge->trx_id) }}')">@lang('global.confirm')</button>
            </div>
        </div> <!-- End Card Body -->
    </div> <!-- End Card -->
@endsection
