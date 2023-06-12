<div class="row mb-5">

    <div class="col-12">

        <div class="card">
            <div class="card-header align-items-center bg-primary card-custom-payment py-3">
                <h5>@lang('global.user_data')</h5>
                <button class="btn btn-sm border border-light text-white"
                    type="button"
                    onclick="openGlobalModal('{{ route('payment_gateaway.user-data-form', $id) }}', '{{ __('global.user_data') }}', {footer: true, target: `target-user-data-action`})">Add</button>
            </div>
            <div class="card-body border border-primary">
                <div id="target-user-data"></div>
            </div> <!-- End Card body -->
        </div> <!-- End Card -->

    </div> <!-- End Col -->

</div><!-- End Row -->
