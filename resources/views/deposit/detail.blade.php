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
    @role('user')
    <div class="card mb-5">
        <div class="card-body py-2">
            <div class="text-start">
                <a class="btn btn-primary btn-sm" href="{{ route('deposit.index') }}">@lang('global.back')</a>
            </div>
        </div> <!-- End Card Body -->
    </div> <!-- End Card -->
    @endrole

    <div class="card">
        <div class="card-body">

            <div class="text-center">
                <h5># {{ $data->charge->trx_id }}</h5>
                <p class="mb-0" style="font-size: 11px;">
                    @lang('global.paid_at')
                    @if ($isManualPayment)
                        <b>{{ \Carbon\Carbon::parse($data->depositManual->created_at)->timezone('Asia/Jakarta') }}</b>
                    @else
                        <b>{{ \Carbon\Carbon::parse($data->depositAutomatic->created_at)->timezone('Asia/Jakarta') }}</b>
                    @endif
                </p>
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
                <div class="table-responsive">
                    <table class="table mt-2">
                        <thead>
                            <tr>
                                <th colspan="6" class="border text-center fw-bolder">{{ $data->charge->gateaway->payment->name }}</th>
                            </tr>
                            <tr>
                                <th class="border ps-3">@lang('global.sender') @lang('global.name')</th>
                                @role('admin')
                                <th class="border">@lang('global.fix_charge')</th>
                                <th class="border">@lang('global.percent_charge')</th>
                                @endrole
                                <th class="border">@lang('global.request_amount')</th>
                                <th class="border">@lang('global.total_transfer')</th>
                                <th class="border">@lang('global.status')</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr class="border">
                                <td>
                                    <div>
                                        <p class="mb-0 fw-bolder ps-3">{{ $data->user->email }}</p>
                                    </div>
                                </td>
                                @role('admin')
                                <td>{{ number_format($data->charge->fixed_charge) }}</td>
                                <td>{{ $data->charge->percent_charge }} %</td>
                                @endrole
                                <td class="fw-bolder">{{ $transaction_data['request_amount'] }}</td>
                                <td class="fw-bolder">{{ $transaction_data['transfer_amount'] }}</td>
                                <td class="fw-bolder">
                                    {!! $status !!}
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            @role('admin')
                @if ($data->depositManual->status == \App\Models\DepositManual::PENDING)
                    <div class="d-flex align-items-center justify-content-between button-action gap-5">
                        <button class="btn btn-secondary w-100"
                            type="button"
                            onclick="openGlobalModal('{{ route('deposit.decline-form', encrypt($data->charge->trx_id)) }}', '{{ __('global.decline_reason') }}', {footer: true, target: 'target-reason-action'})">
                            @lang('global.decline')
                        </button>
                        <button class="btn btn-primary w-100"
                            type="button"
                            onclick="confirmDeposit('{{ base64url_encode($data->charge->trx_id) }}')">@lang('global.confirm')</button>
                    </div>
                @elseif ($data->depositManual->status == \App\Models\DepositManual::DECLINE)
                    <div class="d-flex align-items-center justify-content-between button-action gap-5">
                        <button class="btn btn-primary w-100"
                            type="button"
                            onclick="confirmApproveDeposit('{{ encrypt($data->charge->trx_id) }}')">
                            @lang('global.accept')
                        </button>
                    </div>
                @endif
            @endrole
        </div> <!-- End Card Body -->
    </div> <!-- End Card -->
@endsection

@push('scripts')
    <script src="{{ mix('js/regex.js') }}"></script>
    <script src="{{ mix('js/deposit.js') }}"></script>
@endpush
