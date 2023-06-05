<!--begin::Card-->
<div class="card">

    <div class="card-header py-2 mb-0 min-height-auto align-items-center">

        <h3 class="mb-0">
            @lang('global.info_of')
        </h3>

    </div>

    <div class="card-body">

        <!-- Begin Form -->
        <form id="form-edit-user">

            <div class="row">

                <div class="col-md-6 col-sm-12 mb-3">

                    <div class="form-group">
                        <label for="first_name"
                            class="form-label required">
                            @lang('global.first_name')
                        </label>
                        <x-form.text
                            id="first_name"
                            error="first_name"
                            name="first_name"
                            className="onlyWord"
                            value="{{ $user->first_name }}"
                            :text="__('global.first_name')"
                            ></x-form.text>
                    </div>

                </div>

                <div class="col-md-6 col-sm-12 mb-3">

                    <div class="form-group">
                        <label for="last_name"
                            class="form-label required">
                            @lang('global.last_name')
                        </label>
                        <x-form.text
                            id="last_name"
                            error="last_name"
                            name="last_name"
                            className="onlyWord"
                            value="{{ $user->last_name }}"
                            :text="__('global.last_name')"
                            ></x-form.text>
                    </div>

                </div>

            </div>

            <div class="row">

                <div class="col-md-6 col-sm-12 mb-3">

                    <div class="form-group">
                        <label for="email"
                            class="form-label required">
                            @lang('global.email')
                        </label>
                        <x-form.text
                            id="email"
                            type="email"
                            error="email"
                            name="email"
                            value="{{ $user->email }}"
                            :text="__('global.email')"
                            ></x-form.text>
                    </div>

                </div>

                <div class="col-md-6 col-sm-12 mb-3">

                    <div class="form-group">
                        <label for="phone"
                            class="form-label required">
                            @lang('global.phone')
                        </label>
                        <div class="input-group mb-3">
                            <span class="input-group-text"
                                id="basic-addon1">+62</span>
                            <input type="text"
                                class="form-control phoneFormat"
                                name="phone"
                                id="phone"
                                value="{{ $user->phone }}"
                                placeholder="{{ __('global.phone') }}"
                                aria-describedby="basic-addon1">
                        </div>
                    </div>

                </div>

            </div>

            <div class="row">

                <div class="col-md-12 mb-3">

                    <div class="form-group">

                        <label for="address"
                            class="form-label">
                            @lang('global.address')
                        </label>

                        <x-form.text
                            id="address"
                            error="address"
                            name="address"
                            value="{{ $user->address }}"
                            :text="__('global.address')"></x-form.text>

                    </div>

                </div>

            </div>

            <div class="row">

                <div class="col-md-3 col-sm-6 mb-3">

                    <div class="form-group">

                        <label for="country"
                            class="form-label">
                            @lang('global.country')
                        </label>
                        <select name="country"
                            id="country"
                            class="form-select form-control"
                            data-value="{{ $user->country_id }}">

                            <option value="{{ $user->country_id }}">
                                {{ $user->country_id ? $user->country->name : '' }}
                            </option>

                        </select>

                    </div>

                </div>

                <div class="col-md-3 col-sm-6 mb-3">

                    <div class="form-group">

                        <label for="state"
                            class="form-label">
                            @lang('global.state')
                        </label>
                        <select name="state"
                            id="state"
                            class="form-select form-control"
                            data-value="{{ $user->state_id }}">

                            <option value="{{ $user->state_id }}">
                                {{ $user->state_id ? $user->state->name : '' }}
                            </option>

                        </select>

                    </div>

                </div>

                <div class="col-md-3 col-sm-6 mb-3">

                    <div class="form-group">

                        <label for="city"
                            class="form-label">
                            @lang('global.city')
                        </label>
                        <select name="city"
                            id="city"
                            class="form-select form-control"
                            data-value="{{ $user->city_id }}">

                            <option value="{{ $user->city_id }}">
                                {{ $user->city_id ? $user->city->name : '' }}
                            </option>

                        </select>

                    </div>

                </div>

                <div class="col-md-3 col-sm-6 mb-3">

                    <div class="form-group">

                        <label for="postal"
                            class="form-label">
                            @lang('global.postal')
                        </label>
                        <x-form.text
                            id="postal"
                            error="postal"
                            name="postal"
                            value="{{ $user->postalcode }}"
                            :text="__('global.postal')"
                            className="onlyNumber max-6"></x-form.text>

                    </div>

                </div> <!-- End Column -->

            </div> <!-- End Row -->

            <!-- Row -->
            <div class="row mt-1">

                <!-- Col -->
                <div class="col-md-3 col-sm-6 mb-3">

                    <label for="email_verified" class="form-label">
                        @lang('global.email') @lang('global.verified')
                    </label>
                    <input type="checkbox"
                        data-width="100%"
                        data-onstyle="success"
                        data-offstyle="danger"
                        data-toggle="toggle"
                        data-on="@lang('verified')"
                        data-off="@lang('unverified')"
                        id="ev"
                        name="ev"
                        {{ $user->email_verified_at ? 'checked' : '' }}>

                </div> <!-- End Col -->

                <!-- Col -->
                <div class="col-md-3 col-sm-6 mb-3">

                    <label for="phone_verified" class="form-label">
                        @lang('global.phone') @lang('global.verified')
                    </label>
                    <input type="checkbox"
                        data-width="100%"
                        data-onstyle="success"
                        data-offstyle="danger"
                        data-toggle="toggle"
                        data-on="@lang('global.verified')"
                        data-off="@lang('global.unverified')"
                        name="pv"
                        id="pv"
                        {{ $user->phone_verified_at ? 'checked' : '' }}>

                </div> <!-- End Col -->

                <!-- Col -->
                <div class="col-md-3 col-sm-6 mb-3">

                    <label for="2fa_verified" class="form-label">
                        @lang('global.2fa') @lang('global.verified')
                    </label>
                    <input type="checkbox"
                        data-width="100%"
                        data-onstyle="success"
                        data-offstyle="danger"
                        data-toggle="toggle"
                        data-on="@lang('global.enable')"
                        data-off="@lang('global.disable')"
                        name="fav"
                        id="fav"
                        {{ $user->is_two_factor ? 'checked' : '' }}>

                </div> <!-- End Col -->

                <!-- Col -->
                <div class="col-md-3 col-sm-6 mb-3">

                    <label for="kyc_verified" class="form-label">
                        @lang('global.kyc') @lang('global.verified')
                    </label>
                    <input type="checkbox"
                        data-width="100%"
                        data-onstyle="success"
                        data-offstyle="danger"
                        data-toggle="toggle"
                        data-on="@lang('global.verified')"
                        data-off="@lang('global.unverified')"
                        name="kv"
                        id="kv"
                        {{ $user->kyc_verified_at ? 'checked' : '' }}>

                </div> <!-- End Col -->

            </div> <!-- End Row -->

            <div class="row mt-4">

                <div class="col-12">

                    <button class="btn btn-primary w-100"
                        id="btn-submit"
                        type="button"
                        onclick="submitUser(`{{ $id }}`)">@lang('global.submit')</button>

                </div> <!-- End Col -->

            </div> <!-- End Row -->

        </form> <!-- End Form -->

    </div>

</div>
<!--end::Card-->
