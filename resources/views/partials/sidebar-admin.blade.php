<div class="menu-item">
    <a class="menu-link {{ active_menu(['categories']) }}" href="{{ route('categories.index') }}">
        <span class="menu-icon">
            <!--begin::Svg Icon | path: icons/duotune/general/gen025.svg-->
            <span class="svg-icon svg-icon-2">
                <i class="fa fa-list"></i>
            </span>
            <!--end::Svg Icon-->
        </span>
        <span class="menu-title">@lang('global.category')</span>
    </a>
</div>
<div data-kt-menu-trigger="click" class="menu-item menu-accordion {{ \Request::segment(1) == 'users' ? 'show' : '' }}">
    <span class="menu-link">
        <span class="menu-icon">
            <!--begin::Svg Icon | path: assets/media/icons/duotune/communication/com014.svg-->
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
            <path d="M16.0173 9H15.3945C14.2833 9 13.263 9.61425 12.7431 10.5963L12.154 11.7091C12.0645 11.8781 12.1072 12.0868 12.2559 12.2071L12.6402 12.5183C13.2631 13.0225 13.7556 13.6691 14.0764 14.4035L14.2321 14.7601C14.2957 14.9058 14.4396 15 14.5987 15H18.6747C19.7297 15 20.4057 13.8774 19.912 12.945L18.6686 10.5963C18.1487 9.61425 17.1285 9 16.0173 9Z" fill="currentColor"/>
            <rect opacity="0.3" x="14" y="4" width="4" height="4" rx="2" fill="currentColor"/>
            <path d="M4.65486 14.8559C5.40389 13.1224 7.11161 12 9 12C10.8884 12 12.5961 13.1224 13.3451 14.8559L14.793 18.2067C15.3636 19.5271 14.3955 21 12.9571 21H5.04292C3.60453 21 2.63644 19.5271 3.20698 18.2067L4.65486 14.8559Z" fill="currentColor"/>
            <rect opacity="0.3" x="6" y="5" width="6" height="6" rx="3" fill="currentColor"/>
            </svg>
            <!--end::Svg Icon-->
        </span>
        <span class="menu-title">@lang('global.users')</span>
        <span class="menu-arrow"></span>
    </span>
    <div class="menu-sub menu-sub-accordion">
        <div class="menu-item">
            <a class="menu-link {{ \Request::segment(2) == 'active' ? 'active' : '' }}" href="{{ route('users.index', 'active') }}">
                <span class="menu-bullet">
                    <span class="bullet bullet-dot"></span>
                </span>
                <span class="menu-title">@lang('global.active') @lang('global.users')</span>
            </a>
        </div>
        <div class="menu-item">
            <a class="menu-link {{ \Request::segment(2) == 'banned' ? 'active' : '' }}" href="{{ route('users.index', 'banned') }}">
                <span class="menu-bullet">
                    <span class="bullet bullet-dot"></span>
                </span>
                <span class="menu-title">@lang('global.ban_user')</span>
            </a>
        </div>
        <div class="menu-item">
            <a class="menu-link {{ \Request::segment(2) == 'ue' ? 'active' : '' }}" href="{{ route('users.index', 'ue') }}">
                <span class="menu-bullet">
                    <span class="bullet bullet-dot"></span>
                </span>
                <span class="menu-title">@lang('global.unverified') @lang('global.email')</span>
            </a>
        </div>
        <div class="menu-item">
            <a class="menu-link {{ \Request::segment(2) == 'up' ? 'active' : '' }}" href="{{ route('users.index', 'up') }}">
                <span class="menu-bullet">
                    <span class="bullet bullet-dot"></span>
                </span>
                <span class="menu-title">@lang('global.unverified') @lang('global.phone')</span>
            </a>
        </div>
        <div class="menu-item">
            <a class="menu-link {{ \Request::segment(2) == 'uk' ? 'active' : '' }}" href="{{ route('users.index', 'uk') }}">
                <span class="menu-bullet">
                    <span class="bullet bullet-dot"></span>
                </span>
                <span class="menu-title">@lang('global.unverified') @lang('global.kyc')</span>
            </a>
        </div>
    </div>
</div>

<!-- Setting Menu -->
<div class="menu-item">
    <div class="menu-content pb-2">
        <span class="menu-section text-muted text-uppercase fs-8 ls-1">@lang('global.setting')</span>
    </div>
</div>

<div data-kt-menu-trigger="click" class="menu-item menu-accordion {{ \Request::segment(1) == 'payment-gateaway' ? 'show' : '' }}">
    <span class="menu-link">
        <span class="menu-icon">
            <span class="svg-icon svg-icon-2">
                <i class="fa fa-money-bill"></i>
            </span>
        </span>
        <span class="menu-title">@lang('global.payment_gateaway')</span>
        <span class="menu-arrow"></span>
    </span>
    <div class="menu-sub menu-sub-accordion">
        <div class="menu-item">
            <a class="menu-link {{ \Request::segment(2) == 'manual' ? 'active' : '' }}" href="{{ route('payment-gateaway.index', 'manual') }}">
                <span class="menu-bullet">
                    <span class="bullet bullet-dot"></span>
                </span>
                <span class="menu-title">@lang('global.manual_gateaway')</span>
            </a>
        </div>
    </div>
    <div class="menu-sub menu-sub-accordion">
        <div class="menu-item">
            <a class="menu-link {{ \Request::segment(2) == 'automatic' ? 'active' : '' }}" href="{{ route('payment-gateaway.index', 'automatic') }}">
                <span class="menu-bullet">
                    <span class="bullet bullet-dot"></span>
                </span>
                <span class="menu-title">@lang('global.automatic_gateaway')</span>
            </a>
        </div>
    </div>
</div>
<!-- End Setting Menu -->
