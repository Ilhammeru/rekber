<?php

namespace App\Providers;

use App\Actions\Fortify\CreateNewUser;
use App\Actions\Fortify\ResetUserPassword;
use App\Actions\Fortify\UpdateUserPassword;
use App\Actions\Fortify\UpdateUserProfileInformation;
use App\Models\User;
use App\Models\UserLoginHistory;
use Carbon\Carbon;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\ServiceProvider;
use Laravel\Fortify\Fortify;
use Stevebauman\Location\Facades\Location;

class FortifyServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // custom login
        Fortify::authenticateUsing(function (Request $request) {
            /**
             * User 'BINARY {field}' to handle case sensitive in laravel query
             */
            $user = User::where(DB::raw('BINARY `username`'), $request->username)->first();

            if (
                $user &&
                Hash::check($request->password, $user->password)
                ) {
                    session()->put('session_save_history', true);
                    session()->put('user_data', $user);
                    return $user;
            }
        });

        // reset password link
        Fortify::resetPasswordView(function ($request) {
            return view('auth.passwords.reset', ['request' => $request]);
        });

        // forgot password view
        Fortify::requestPasswordResetLinkView(function () {
            return view('auth.passwords.forgot');
        });

        Fortify::createUsersUsing(CreateNewUser::class);
        Fortify::updateUserProfileInformationUsing(UpdateUserProfileInformation::class);
        Fortify::updateUserPasswordsUsing(UpdateUserPassword::class);
        Fortify::resetUserPasswordsUsing(ResetUserPassword::class);


        RateLimiter::for('login', function (Request $request) {
            $email = (string) $request->username;

            return Limit::perMinute(20)->by($email.$request->ip());
        });

        RateLimiter::for('two-factor', function (Request $request) {
            return Limit::perMinute(20)->by($request->session()->get('login.id'));
        });
    }
}
