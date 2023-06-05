@extends('layouts.master')

@section('content')

<!--begin::Card-->
<div class="card">

    <div class="card-body">

        <div class="table-responsive">
            <table class="table" id="table-users">
                <thead>
                    <tr class="fw-bold fs-5">
                        <th>#</th>
                        <th>@lang('global.name')</th>
                        <th>@lang('global.email')</th>
                        <th>@lang('global.joined_at')</th>
                        <th>@lang('global.balance')</th>
                        <th>@lang('global.action')</th>
                        <th>created at</th>
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
    init('table-users', '{{ $status }}')
</script>
@endpush
