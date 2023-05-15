@extends('email_template.layout')
@section('content')
    <p>@lang('global.hello'), <strong>{{ $name }}</strong></p>
    <br />
    <p style="text-align:justify;">@lang('global.here_your_otp_for_email')</p>
    <p style="text-align:justify;">@lang('global.keep_secret')</p>
    <h1 style="text-align:center;"><strong><?= implode(' ', str_split($otp)) ?></strong></h1>
    <br />
    <br />
@endsection
