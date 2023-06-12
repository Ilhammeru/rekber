@extends('layouts.master')

@section('content')

<!--begin::Card-->
<div class="card">

    <div class="card-body">

        <div class="text-end">
            <a class="btn btn-sm btn-success"
                href="{{ route('payment-gateaway.create') }}">@lang('global.create')</a>
        </div>

        <div class="table-responsive">
            <table class="table" id="table-payment-gateaway">
                <thead>
                    <tr class="fw-bold fs-5">
                        <th>#</th>
                        <th>@lang('global.name')</th>
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
