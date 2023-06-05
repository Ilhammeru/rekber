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
            <table class="table" id="table-user-login-history">
                <thead>
                    <tr class="fw-bold fs-5">
                        <th>#</th>
                        <th>@lang('global.user')</th>
                        <th>@lang('global.login_at')</th>
                        <th>@lang('global.ip')</th>
                        <th>@lang('global.location')</th>
                        <th>@lang('global.device')</th>
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
    initLoginHistory(`{{ $id }}`)
</script>
@endpush
