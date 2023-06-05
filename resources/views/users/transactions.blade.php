@extends('layouts.master')

@section('content')

<div class="card card-body mb-5">
    <div class="row">
        <div class="col-md-3">
            <a class="btn btn-primary btn-sm" href="{{ route('users.show', $id) }}">
                Back
            </a>
        </div>
    </div>
</div>

<!--begin::Card-->
<div class="card">

    <div class="card-body">

        <div class="table-responsive">
            <table class="table" id="table-transactions">
                <thead>
                    <tr class="fw-bold fs-5">
                        <th>#</th>
                        <th>@lang('global.user')</th>
                        <th>@lang('global.trx')</th>
                        <th>@lang('global.description')</th>
                        <th>@lang('global.amount')</th>
                        <th>@lang('global.post_balance')</th>
                    </tr>
                </thead>
            </table>
        </div>

    </div>

</div>
<!--end::Card-->

@endsection

@push('scripts')
<script src="{{ mix('js/user.js') }}"></script>
<script>
    initTransactions(`{{ $id }}`)
</script>
@endpush
