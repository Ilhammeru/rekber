@extends('layouts.master')

@section('content')

<!--begin::Card-->
<div class="card">

    <div class="card-body">

        <div class="text-end">
            <a class="btn btn-sm btn-success"
                href="{{ route('deposit.create') }}">@lang('global.create')</a>
        </div>

        <div class="table-responsive">
            <table class="table" id="table-deposit">
                <thead>
                    <tr class="fw-bold fs-5">
                        <th>#</th>
                        <th>@lang('global.amount')</th>
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
<script src="{{ mix('js/deposit.js') }}"></script>
@endpush
