<div class="row">
    <div class="col-md-6 col-lg-4 col-xl-3 mb-5">
        <x-widget.tiny-card
            icon="money-bag.png"
            heading="0"
            :text="__('global.balance')"
            colorIcon="bg-primary"
            idHeading="heading-balance">
            <x-slot:actionSlot>
                <a class="action-card-box fs-10 cursor-pointer hover-bg-warning border border-warning text-warning"
                    href="{{ route('users.transactions', $id) }}">
                    @lang('global.view')
                </a>
            </x-slot>
        </x-widget.tiny-card>
    </div>

    <div class="col-md-6 col-lg-4 col-xl-3 mb-5">
        <x-widget.tiny-card
            icon="wallet.png"
            heading="1"
            :text="__('global.deposited')"
            colorIcon="bg-success">
            <x-slot:actionSlot>
                <a class="action-card-box fs-10 cursor-pointer hover-bg-success border border-success text-success"
                    href="#">
                    @lang('global.view')
                </a>
            </x-slot>
        </x-widget.tiny-card>
    </div>

    <div class="col-md-6 col-lg-4 col-xl-3 mb-5">
        <x-widget.tiny-card
            icon="wallet.png"
            heading="1"
            :text="__('global.withdrawal')"
            colorIcon="bg-orange">
            <x-slot:actionSlot>
                <a class="action-card-box fs-10 cursor-pointer hover-bg-orange border-orange text-orange"
                    href="#">
                    @lang('global.view')
                </a>
            </x-slot>
        </x-widget.tiny-card>
    </div>

    <div class="col-md-6 col-lg-4 col-xl-3 mb-5">
        <x-widget.tiny-card
            icon="transaction.png"
            heading="1"
            :text="__('global.transactions')"
            colorIcon="bg-purple">
            <x-slot:actionSlot>
                <a class="action-card-box fs-10 cursor-pointer hover-bg-purple border-purple text-purple"
                    href="#">
                    @lang('global.view')
                </a>
            </x-slot>
        </x-widget.tiny-card>
    </div>

    <div class="col-md-6 col-lg-4 col-xl-3 mb-5">
        <x-widget.tiny-card
            icon="funded.png"
            heading="1"
            :text="__('global.milestone_funded')"
            colorIcon="bg-purple">
        </x-widget.tiny-card>
    </div>

    <div class="col-md-6 col-lg-4 col-xl-3 mb-5">
        <x-widget.tiny-card
            icon="handshake.png"
            heading="1"
            :text="__('global.total') . ' ' . __('global.escrow')"
            colorIcon="bg-primary">
        </x-widget.tiny-card>
    </div>

    <div class="col-md-6 col-lg-4 col-xl-3 mb-5">
        <x-widget.tiny-card
            icon="circular.png"
            heading="1"
            :text="__('global.running_escrow')"
            colorIcon="bg-success">
        </x-widget.tiny-card>
    </div>

    <div class="col-md-6 col-lg-4 col-xl-3 mb-5">
        <x-widget.tiny-card
            icon="loading.png"
            heading="1"
            :text="__('global.waiting_for_accept')"
            colorIcon="bg-orange">
        </x-widget.tiny-card>
    </div>

    <div class="col-md-6 col-lg-4 col-xl-3 mb-5">
        <x-widget.tiny-card
            icon="check.png"
            heading="1"
            :text="__('global.completed') . ' ' . __('global.escrow') "
            colorIcon="bg-success">
        </x-widget.tiny-card>
    </div>

    <div class="col-md-6 col-lg-4 col-xl-3 mb-5">
        <x-widget.tiny-card
            icon="stop.png"
            heading="1"
            :text="__('global.disputed') . ' ' . __('global.escrow') "
            colorIcon="bg-danger">
        </x-widget.tiny-card>
    </div>

    <div class="col-md-6 col-lg-4 col-xl-3 mb-5">
        <x-widget.tiny-card
            icon="cancel.png"
            heading="1"
            :text="__('global.canceled') . ' ' . __('global.escrow') "
            colorIcon="bg-light-red">
        </x-widget.tiny-card>
    </div>

    <div class="col-md-6 col-lg-4 col-xl-3 mb-5">
        <x-widget.tiny-card
            icon="ticket.png"
            heading="1"
            :text="__('global.total_ticket')"
            colorIcon="bg-orange">
        </x-widget.tiny-card>
    </div>
</div>
