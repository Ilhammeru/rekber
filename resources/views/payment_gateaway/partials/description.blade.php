<div class="row mb-5">

    <div class="col-12">

        <div class="card">
            <div class="card-header bg-primary card-custom-payment py-3">
                <h5>@lang('global.deposit') @lang('global.instruction')</h5>
            </div>
            <div class="card-body border border-primary">

                <div class="form-group mb-3">
                    <div id="instruction" data-value="{{ $data ? $data->detail->deposit_instruction : '' }}"></div>
                    <div class="invalid-feedback" id="error-instruction"></div>
                </div>

            </div> <!-- End Card body -->
        </div> <!-- End Card -->

    </div> <!-- End Col -->

</div><!-- End Row -->
