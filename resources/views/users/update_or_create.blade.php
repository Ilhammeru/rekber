<form id="form-category">

    <div class="form-group">
        <label for="name" class="form-label required">@lang('global.category_name')</label>
        <x-form.text
            id="name"
            error="name"
            name="name"
            value="{{ isset($category) ? $category->name : '' }}"
            :text="__('global.category_name')"></x-form.text>

        <input type="hidden"
            value="{{ isset($category) ? $category->id : null }}"
            name="id"
            id="id">
    </div> <!-- End Form Group -->

</form>

<div class="target-category-action">
    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
    <button type="button" class="btn btn-primary"
        onclick="submitCategory(`{{ isset($category) ? 'update' : 'store' }}`)">
        @lang('global.save')
    </button>
</div>
