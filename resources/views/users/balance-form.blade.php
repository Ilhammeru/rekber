<form id="form-balance">

    <div class="form-group mb-4">
        <label for="name" class="form-label">@lang('global.balance')</label>
        <x-form.text
            id="balance"
            error="balance"
            name="balance"
            className="onlyNumber"
            :text="__('global.balance')"></x-form.text>

        <input type="hidden" value="{{ $type }}" name="type">
    </div> <!-- End Form Group -->

    <div class="form-group mb-4">
        <label for="description" class="form-label">@lang('global.description')</label>
        <x-form.text
            id="description"
            error="description"
            name="description"
            :text="__('global.description')"></x-form.text>
    </div> <!-- End Form Group -->

</form>

<div class="target-balance-action">
    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
    <button type="button" class="btn btn-primary"
        onclick="submitBalance(`{{ $id }}`)">
        @lang('global.save')
    </button>
</div>
