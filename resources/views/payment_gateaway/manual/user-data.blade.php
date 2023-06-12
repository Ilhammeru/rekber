@if ($details)
    <div class="row">
        @foreach ($details as $kk => $detail)
            <div class="col-md-4">
                <div class="card card-body border border-secondary">
                    <div class="form-group mb-4">
                        <label for="" class="form-label">@lang('global.type')</label>
                        <input type="text"
                            readonly
                            name="user_data[{{$kk}}][type]"
                            class="form-control bg-light"
                            value="{{ $detail['type'] }}">
                    </div>
                    <div class="form-group mb-4">
                        <label for="" class="form-label">@lang('global.label')</label>
                        <input type="text"
                            class="form-control bg-light"
                            name="user_data[{{$kk}}][label]"
                            readonly
                            value="{{ $detail['label'] }}">
                    </div>

                    <div class="form-group mb-4 d-none">
                        <label for="is_required" class="form-label">@lang('global.is_required')</label>
                        <input type="text"
                            class="form-control bg-light"
                            name="user_data[{{$kk}}][is_required]"
                            readonly
                            value="{{ $detail['is_required'] }}">
                    </div>

                    <div class="d-flex align-items-center">
                        <button class="btn btn-success btn-sm w-100"
                            type="button"
                            onclick="openGlobalModal('{{ route('payment-gateaway.user-data.edit', ['id' => $id, 'key' => $kk]) }}', `{{ __('global.edit') . ' ' . __('global.user_data') }}`, {footer: true, target: 'target-user-data-action'})">
                            <i class="fa fa-pen"></i>
                        </button>
                        <button class="btn btn-warning btn-sm w-100"
                            type="button"
                            onclick="deleteUserData({{ $kk }})">
                            <i class="fa fa-trash"></i>
                        </button>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
@endif

<div class="invalid-feedback" id="error-user_data"></div>
