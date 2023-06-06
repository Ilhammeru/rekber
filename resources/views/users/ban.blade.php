<form id="form-ban">

    <div class="form-group">
        <label for="reason" class="form-label required">@lang('global.reason')</label>
        <x-form.text
            id="reason"
            error="reason"
            name="reason"
            :text="__('global.reason')"></x-form.text>
    </div> <!-- End Form Group -->

</form>

<div class="target-ban-action">
    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
    <button type="button" class="btn btn-primary"
        onclick="banUser('{{ $id }}')">
        @lang('global.submit')
    </button>
</div>
