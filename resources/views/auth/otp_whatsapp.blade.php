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
            <form method="POST" action="#" id="form-otp-whatsapp">
                <div class="text-center mb-5">
                    <h3>@lang('global.insert_otp')</h3>
                </div>

                <div class="form-group mb-3">
                    <label for="whatsapp" class="form-label">@lang('global.phone')</label>
                    <div class="d-flex align-items gap-4">
                        <x-form.text
                            error="whatsapp"
                            name="whatsapp"
                            id="whatsapp"
                            :text="__('global.whatsapp')"
                            className="text-center phoneFormat"
                            value="{{ $phone }}">
                        </x-form.text>
                        <button class="btn btn-success w-50"
                            type="button"
                            id="send-otp-whatsapp-btn"
                            onclick="sendOtp('{{ $phone }}')">@lang('global.send') @lang('global.otp')</button>
                    </div>
                </div>

                <div class="form-group mb-3 d-none" id="row-otp-whatsapp">
                    <x-form.text
                        error="otp"
                        id="otp"
                        className="text-center onlyNumber"
                        maxlength="4"
                        minlength="4"
                        name="otp"
                        :text="__('global.otp')"></x-form.text>

                    <input type="hidden" id="helper" value="{{ $email }}">
                </div>

                <div class="form-group">
                    <button class="btn btn-primary w-100"
                        id="btn-submit-otp"
                        disabled
                        type="button"
                        onclick="verifiedPhoneOtp()">
                        @lang('global.send')
                    </button>
                </div>

                <!--begin::Link-->
                <div class="text-gray-400 fw-bold fs-4 mt-3">
                    @lang('global.back_to_email_verification')
                    <a href="{{ url('register/otp?via=email&g=' . $email) }}" class="link-primary fw-bolder">@lang('global.here')</a>
                </div>
                <!--end::Link-->

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
@endpush
