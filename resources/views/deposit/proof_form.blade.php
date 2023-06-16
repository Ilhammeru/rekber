@extends('layouts.master')

@section('content')
    <div class="card">
        <div class="card-body">
            @if ($raw->depositManual->status == 2)
                <form id="form-confirm-payment" enctype="multipart/form-data">
                    <div class="row">
                        @foreach ($fields as $field)
                            @php
                                $label = $field['label'];
                                $slug = strtolower(implode('_', explode(' ', $label)));
                            @endphp
                            <div class="col-md-6 col-sm-12">
                                <div class="form-group mb-4">
                                    <label for="{{ $field['label'] }}" class="form-label {{ $field['is_required'] ? 'required' : '' }}">{{ $field['label'] }}</label>
                                    @if (strtolower($field['type']) == 'file')
                                        <input type="file"
                                            class="form-control"
                                            name="{{$slug}}"
                                            id="{{ $slug }}"
                                            accept=".png,.jpg,.jpeg,.webp">
                                        <div class="invalid-feedback" id="error-{{ $slug }}"></div>
                                    @elseif (strtolower($field['type']) == 'text')
                                        <input type="text"
                                            class="form-control"
                                            name="{{ $slug }}"
                                            id="{{ $slug }}">
                                        <div class="invalid-feedback" id="error-{{ $slug }}"></div>
                                    @endif
                                </div><!-- End Form Group -->
                            </div>
                        @endforeach
                        <div class="col-md-12">
                            <button class="btn btn-primary"
                                type="button"
                                onclick="sendPaymentProof('{{ $trx }}')">
                                @lang('global.submit')
                            </button>
                        </div>
                    </div>
                </form> <!-- End Form -->
            @endif
        </div> <!-- End Card Body -->
    </div> <!-- End Card -->
@endsection

@push('scripts')
    <script src="{{ mix('js/deposit.js') }}"></script>
@endpush
