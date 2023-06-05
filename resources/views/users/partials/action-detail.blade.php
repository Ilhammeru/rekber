<div class="row">
    <div class="col-md-2 col-sm-4 col-lg-2 mb-5">
        <button class="btn btn-sm btn-action-detail w-100 bg-success d-flex align-items-center justify-content-center gap-4 text-white"
            type="button"
            onclick="openGlobalModal('{{ route('users.add-deduct-balance', ['id' => $id, 'type' => 'add']) }}', `{{ __('global.add') . ' ' . __('global.balance') }}`, {footer: true, target: `target-balance-action`})">
            <img src="{{ asset('images/add.png') }}" id="icon-add" alt="">
            @lang('global.balance')
        </button>
    </div>
    <div class="col-md-2 col-sm-4 col-lg-2 mb-5">
        <button class="btn btn-sm btn-action-detail w-100 bg-danger d-flex align-items-center justify-content-center gap-4 text-white"
            type="button"
            onclick="openGlobalModal('{{ route('users.add-deduct-balance', ['id' => $id, 'type' => 'deduct']) }}', `{{ __('global.deduct') . ' ' . __('global.balance') }}`, {footer: true, target: `target-balance-action`})">
            <img src="{{ asset('images/minus.png') }}" id="icon-add" alt="">
            @lang('global.balance')
        </button>
    </div>
    <div class="col-md-2 col-sm-4 col-lg-2 mb-5">
        <a class="btn btn-sm btn-action-detail w-100 bg-primary d-flex align-items-center justify-content-center gap-4 text-white"
            href="{{ route('users.logins', $id) }}">
            <img src="{{ asset('images/clipboard.png') }}" id="icon-add" alt="">
            @lang('global.logins')
        </a>
    </div>
    <div class="col-md-2 col-sm-4 col-lg-2 mb-5">
        <button class="btn btn-sm btn-action-detail w-100 bg-dark-grey d-flex align-items-center justify-content-center gap-4 text-white"
            type="button">
            <img src="{{ asset('images/bell.png') }}" id="icon-add" alt="">
            @lang('global.notifications')
        </button>
    </div>
    <div class="col-md-2 col-sm-4 col-lg-2 mb-5">
        <a class="btn btn-sm btn-action-detail w-100 bg-primary d-flex align-items-center justify-content-center gap-4 text-white"
            href="{{ route('users.login', $id) }}"
            target="_blank">
            <img src="{{ asset('images/enter.png') }}" id="icon-add" alt="">
            @lang('global.login_as_user')
        </a>
    </div>
    <div class="col-md-2 col-sm-4 col-lg-2 mb-5">
        <button class="btn btn-sm btn-action-detail w-100 bg-warning d-flex align-items-center justify-content-center gap-4 text-white"
            type="button">
            <img src="{{ asset('images/cancel.png') }}" id="icon-add" alt="">
            @lang('global.ban_user')
        </button>
    </div>
</div>
