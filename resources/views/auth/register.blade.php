@extends('layouts.auth')

@push('styles')
    <style>
        .select2 {
            display: block;
            width: 100%;
            padding: 0.75rem 1rem;
            font-size: 1.1rem;
            font-weight: 500;
            line-height: 1.5;
            color: #181c32;
            background-color: #fff;
            background-clip: padding-box;
            border: 1px solid #e4e6ef;
            appearance: none;
            border-radius: 0.475rem;
            box-shadow: inset 0 1px 2px rgb(0 0 0 / 8%);
            transition: border-color .15s ease-in-out,box-shadow .15s ease-in-out;
        }

        .select2-container--default .select2-selection--single {
            border: none;
        }

        .select2-container--default .select2-selection--single .select2-selection__arrow {
            top: 10px;
        }
        .form-gorup .helper {
            font-size: 12px;
            color: orangered;
        }
    </style>
@endpush

@section('content')
{{-- php condition --}}
@php
    $disable = true;
@endphp
{{-- end php condition --}}

<!--begin::Content-->
<div class="d-flex flex-center flex-column flex-column-fluid p-10 pb-lg-20">
    <!--begin::Logo-->
    <a href="{{ route('dashboard') }}" class="mb-12">
        <img alt="Logo" src="{{ asset('images/logo-1.svg') }}" class="h-40px" />
    </a>
    <!--end::Logo-->
    <!--begin::Wrapper-->
    <div class="w-lg-500px bg-body rounded shadow-sm p-10 p-lg-15 mx-auto body-register">
        <!--begin::Form-->
            <form method="POST" action="#" id="form-register">
                <!--begin::Heading-->
                <div class="text-center mb-10">
                    <!--begin::Title-->
                    <h1 class="text-dark mb-3">@lang('global.register')</h1>
                    <!--end::Title-->
                    <!--begin::Link-->
                    <div class="text-gray-400 fw-bold fs-4">@lang('global.already_have_account')
                    <a href="{{ route('login') }}" class="link-primary fw-bolder">@lang('global.here')</a></div>
                    <!--end::Link-->
                </div>

                <div class="form-group mb-3">
                    <label for="username" class="form-label">@lang('global.username')</label>
                    <x-form.text
                        error="username"
                        id="username"
                        name="username"
                        :text="__('global.username')"></x-form.text>
                </div>

                <!-- form group -->
                <div class="form-group mb-3">
                    <label for="phone" class="form-label">@lang('global.phone')</label>
                    <x-form.text
                        error="phone"
                        className="phoneFormat"
                        id="phone"
                        name="phone"
                        :text="__('global.phone')"></x-form.text>
                    <div class="helper d-none" id="helper-phone">@lang('global.phone_rule')</div>
                </div> <!-- End form group -->

                <!-- form group -->
                <div class="form-group mb-3">
                    <label for="email" class="form-label">@lang('global.email')</label>
                    <x-form.text
                        error="email"
                        type="email"
                        id="email"
                        name="email"
                        :text="__('global.email')"></x-form.text>
                </div> <!-- End form group -->

                <!-- form group -->
                <div class="form-group mb-3 position-relative">
                    <label for="password" class="form-label">@lang('global.password')</label>
                    <x-form.text
                        error="password"
                        type="password"
                        id="password"
                        name="password"
                        :text="__('global.password')"></x-form.text>
                    <div class="helper d-none" id="helper-password">@lang('global.password_rule')</div>
                </div> <!-- End form group -->

                <div class="form-group">
                    <button class="btn btn-primary w-100"
                        id="btn-register"
                        type="button">
                        @lang('global.register')
                    </button>
                </div>

            </form>
        <!--end::Form-->
    </div>
    <!--end::Wrapper-->
</div>
<!--end::Content-->
@endsection

@push('scripts')
    <script src="{{ mix('js/regex.js') }}"></script>
    <script src="{{ mix('js/auth.js') }}"></script>
    <script>
        $('#phone').on('input', (e) => {
            $('#helper-phone').removeClass('d-none');
        });
        $('#phone').on('blur', (e) => {
            $('#helper-phone').addClass('d-none');
        });
        $('#password').on('input', (e) => {
            $('#helper-password').removeClass('d-none');
        });
        $('#password').on('blur', (e) => {
            $('#helper-password').addClass('d-none');
        });
    </script>
@endpush
