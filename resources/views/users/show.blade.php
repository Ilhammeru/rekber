@extends('layouts.master')

@push('styles')
    <link rel="stylesheet" href="{{ asset('plugins/custom/bootstrap-toggle-master/css/bootstrap-toggle.min.css') }}">
    <style>
        #icon-add {
            width: 15px;
            height: 15px;
        }

        .btn-action-detail {
            transition: ease .2s;
        }

        .btn-action-detail:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 15px 0 rgba(40, 199, 111, 0.45);
        }

        .toggle.collapsible.collapsed .toggle-on, .toggle:not(.collapsible):not(.active) .toggle-on {
            display: block;
        }
    </style>
@endpush

@section('content')

@include('users.partials.card-widget')
@include('users.partials.action-detail')
@include('users.partials.form-detail')

@endsection

@push('scripts')
<script src="{{ asset('plugins/custom/bootstrap-toggle-master/js/bootstrap-toggle.min.js') }}"></script>
<script src="{{ mix('js/regex.js') }}"></script>
<script src="{{ mix('js/user.js') }}"></script>
<script>
    updateBalance("{{ encrypt($user->id) }}");
</script>
@endpush
