@extends('layouts.master')

@section('content')

<!--begin::Card-->
<div class="card">

    <div class="card-body">

        <div class="text-end mb-5">
            <a class="btn btn-success"
                type="button"
                href="{{ route('users.notification-form', $id) }}">
                @lang('global.send') @lang('global.notification')
            </a>
        </div>

        <div class="table-responsive">
            <table class="table" id="table-user-notification">
                <thead>
                    <tr class="fw-bold fs-5">
                        <th>#</th>
                        <th>@lang('global.user')</th>
                        <th>@lang('global.status')</th>
                        <th>@lang('global.sender')</th>
                        <th>@lang('global.subject')</th>
                        <th>@lang('global.message')</th>
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
    initNotification('{{ $id }}')
</script>
@endpush
