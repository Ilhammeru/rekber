@php
    $current_type = null;
    $current_key = null;
    $current_required = null;
    $current_label = null;

    if ($field) {
        $current_type = $field['type'];
        $current_key = $key;
        $current_required = $field['is_required'];
        $current_label = $field['label'];
    }
@endphp
<form id="form-user-data">
    <input type="hidden" name="current_key" value="{{ $current_key }}">
    <div class="form-group mb-4">
        <label class="form-label required" for="type">@lang('global.type')</label>
        <select name="type" id="type" class="form-control">
            @foreach ($types as $key => $type)
                <option value="{{ $key }}" {{ $current_type == $key ? 'selected' : '' }}>{{ $type }}</option>
            @endforeach
        </select>
        <div class="invalid-feedback" id="error-type"></div>
    </div><!-- End Form Group -->
    <div class="form-group mb-4">
        <label class="form-label required" for="type">@lang('global.name')</label>
        <x-form.text
            id="name"
            name="name"
            error="name"
            value="{{ $current_label }}"
            text="name"></x-form.text>
    </div><!-- End Form Group -->
    <div class="form-group mb-4">
        <label class="form-label required" for="is_required">@lang('global.is_required')</label>
        <select name="is_required"
            id="is_required"
            class="form-control">
            <option value="1" {{ $current_required == 1 ? 'selected' : '' }}>Required</option>
            <option value="2" {{ $current_required == 0 ? 'selected' : '' }}>Optional</option>
        </select>
        <div class="invalid-feedback" id="error-is_required"></div>
    </div><!-- End Form Group -->
</form>

<div class="target-user-data-action">
    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
    <button class="btn btn-primary"
        id="btn-submit-user-data"
        type="button"
        onclick="submitUserData('{{ $id }}')">
        @lang('global.submit')
    </button>
</div>
