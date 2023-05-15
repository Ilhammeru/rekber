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

<!--begin::Content-->
<div class="d-flex flex-center flex-column flex-column-fluid p-10 pb-lg-20">
    <!--begin::Logo-->
    <a href="{{ route('dashboard') }}" class="mb-12">
        <img alt="Logo" src="{{ asset('images/logo-1.svg') }}" class="h-40px" />
    </a>
    <!--end::Logo-->
    <!--begin::Wrapper-->
    <div class="w-lg-600px bg-body rounded shadow-sm p-10 p-lg-15 mx-auto body-register">
        <h4 class="mb-5 text-center">@lang('global.email_has_been_verified')</h4>

        <a class="btn btn-primary w-100 mt-5" href="{{ route('login') }}">
            @lang('global.login')
        </a>
    </div>
    <!--end::Wrapper-->
</div>
<!--end::Content-->
@endsection
