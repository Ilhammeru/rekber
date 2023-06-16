<form id="form-decline">
    <div class="form-group mb-4">
        <label for="reason" class="form-label">@lang('global.reason')</label>
        <textarea name="reason"
            id="reason"
            cols="3"
            rows="3"
            class="form-control"></textarea>
        <div class="invalid-feedback" id="error-reason"></div>
    </div>
</form>

<div class="target-reason-action">
    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
    <button type="button" class="btn btn-primary"
        onclick="submitReason('{{ $trx }}')">
        @lang('global.save')
    </button>
</div>
