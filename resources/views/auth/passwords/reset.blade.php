@extends('layouts.auth')

@section('content')
<!--begin::Content-->
<div class="d-flex flex-center flex-column flex-column-fluid p-10 pb-lg-20">
    <!--begin::Logo-->
    <a href="{{ route('dashboard') }}" class="mb-5">
        <img alt="Logo" src="{{ asset('images/logo-1.svg') }}" class="h-40px" />
    </a>
    <!--end::Logo-->
    <!--begin::Wrapper-->
    <div class="w-lg-700px bg-body rounded shadow-sm p-10 p-lg-15 mx-auto">
        <form method="POST" action="/reset-password">
            @csrf

            {{-- <input type="hidden" name="token" value="{{ $token }}"> --}}

            @if (session('status'))
                <div class="alert alert-info">
                    <div class="mb-4 text-center">
                        {{ session('status') }}
                    </div>
                </div>
            @endif

            <div class="form-group mb-3">

                <label for="email" class="form-label text-md-end">{{ __('Email Address') }}</label>

                <input id="email"
                    type="email"
                    class="form-control @error('email') is-invalid @enderror"
                    name="email" value="{{ $email ?? old('email') }}"
                    autocomplete="off"
                    autofocus>

                @error('email')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>

            <div class="form-group mb-3">
                <label for="password" class="form-label">@lang('global.password')</label>
                <input type="password"
                    class="form-control @error('password') is-invalid @enderror"
                    id="password"
                    name="password">
                <div class="helper d-none" id="helper-password">@lang('global.password_rule')</div>
                @error('password')
                <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="password_confirmation" class="form-label">@lang('global.password_confirmation')</label>
                <input type="password"
                    class="form-control @error('password_confirmation') is-invalid @enderror"
                    id="password_confirmation"
                    name="password_confirmation">
                @error('password_confirmation')
                <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <input type="hidden" name="token" value="{{ request()->route('token') }}">

            <div class="form-group mt-5">
                <button type="submit" class="btn btn-primary w-100">
                    {{ __('Reset Password') }}
                </button>
            </div>
        </form>
    </div>
    <!--end::Wrapper-->
</div>
<!--end::Content-->
@endsection

@push('scripts')
    <script src="{{ mix('js/regex.js') }}"></script>
    <script>
        $('#password').on('input', (e) => {
            $('#helper-password').removeClass('d-none');
        });
        $('#password').on('blur', (e) => {
            $('#helper-password').addClass('d-none');
        });
    </script>
@endpush
