@extends('layouts.master')

@section('content')

<!--begin::Card-->
<div class="card">

    <div class="card-body">

        <div class="table-responsive">
            <table class="table" id="table-auto-payment-gateaway">
                <thead>
                    <tr class="fw-bold fs-5">
                        <th>#</th>
                        <th>@lang('global.name')</th>
                        <th>@lang('global.enable') @lang('global.currency')</th>
                        <th>@lang('global.status')</th>
                        <th>@lang('global.action')</th>
                    </tr>
                </thead>
            </table>
        </div>

    </div>

</div>
<!--end::Card-->

@endsection

@push('scripts')
<script src="{{ mix('js/payment_gateaway.js') }}"></script>
<script>
    init('{{ $type }}');
</script>
@endpush
