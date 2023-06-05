@extends('layouts.master')

@section('content')

<!--begin::Card-->
<div class="card">

    <div class="card-body">

        <div class="text-end">
            <button class="btn btn-sm btn-success"
                type="button"
                onclick="openGlobalModal('{{ route('categories.create') }}', `{{ __('global.create_category') }}`, {footer: true, target: `target-category-action`})">@lang('global.create')</button>
        </div>

        <div class="table-responsive">
            <table class="table" id="table-category">
                <thead>
                    <tr class="fw-bold fs-5">
                        <th>#</th>
                        <th>@lang('global.category')</th>
                        <th>@lang('global.status')</th>
                        <th>created at</th>
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
<script src="{{ mix('js/category.js') }}"></script>
@endpush
