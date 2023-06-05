<div class="menu-item">
    <a class="menu-link {{ active_menu(['escrow']) }}" href="{{ route('categories.index') }}">
        <span class="menu-icon">
            <!--begin::Svg Icon | path: icons/duotune/general/gen025.svg-->
            <span class="svg-icon svg-icon-2">
                <i class="fa fa-laptop-house"></i>
            </span>
            <!--end::Svg Icon-->
        </span>
        <span class="menu-title">@lang('global.escrow')</span>
    </a>
    <a class="menu-link {{ active_menu(['deposit']) }}" href="{{ route('deposit.index') }}">
        <span class="menu-icon">
            <!--begin::Svg Icon | path: icons/duotune/general/gen025.svg-->
            <span class="svg-icon svg-icon-2">
                <i class="fa fa-money"></i>
            </span>
            <!--end::Svg Icon-->
        </span>
        <span class="menu-title">@lang('global.deposit')</span>
    </a>
</div>
