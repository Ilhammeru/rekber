@extends('layouts.master')

@push('styles')
<link href="//cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">
<link href="//cdn.quilljs.com/1.3.6/quill.bubble.css" rel="stylesheet">
<style>
    .ql-editor {
        height: 300px;
    }
</style>
@endpush

@section('content')

<div class="card card-body mb-5">
    <div class="text-start">
        <a href="{{ route('users.notification', $id) }}" class="btn btn-primary">@lang('global.back')</a>
    </div>
</div>

<!--begin::Card-->
<div class="card">

    <div class="card-body">

        <form id="form-manual-notification">

            <div class="form-group mb-4">

                <label for="subject" class="form-label">@lang('global.subject')</label>
                <x-form.text
                    id="subject"
                    error="subject"
                    name="subject"
                    :text="__('global.subject')"></x-form.text>

            </div> <!-- End Form group -->

            <div class="form-group mb-4">

                <label for="message" class="form-label">@lang('global.message')</label>

                <div id="editor">
                </div>

                <div class="invalid-feedback" id="error-message"></div>

            </div> <!-- End Form group -->

            <div class="form-group mb-4">

                <div class="text-end">
                    <button class="btn btn-success"
                        id="btn-send"
                        type="button"
                        onclick="sendManualNotification('{{ $id }}')">
                        @lang('global.send')
                    </button>
                </div>

            </div>

        </form> <!-- End Form -->

    </div>

</div>
<!--end::Card-->

@endsection

@push('scripts')
<script src="https://cdn.quilljs.com/1.3.6/quill.js"></script>
<script src="{{ mix('js/user.js') }}"></script>
<script>
    initQuill();
</script>
@endpush
