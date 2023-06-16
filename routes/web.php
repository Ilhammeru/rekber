<?php

use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DepositController;
use App\Http\Controllers\PaymentGateawaySettingController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('dummy', function () {
    $data = [
        'receiver' => 'gumilang.dev@gmail.com',
        'receiver_name' => 'Ilham',
        'subject' => 'Rekber OTP Verification',
        'service' => 'email_otp',
        'content' => [
            'otp' => 2234,
            'name' => 'Ilham',
        ],
    ];

    return sendEmail($data);
});

// Localization
Route::get('/js/lang.js', function () {
    $strings = Cache::rememberForever('lang.js', function () {
        $lang = config('app.locale');

        $files = glob(resource_path('lang/'.$lang.'/*.php'));
        $strings = [];

        foreach ($files as $file) {
            $name = basename($file, '.php');
            $strings[$name] = require $file;
        }

        return $strings;
    });

    header('Content-Type: text/javascript');
    echo 'window.i18n = '.json_encode($strings).';';
    exit();
})->name('assets.lang');

Route::get('/user', function() {
    $pageTitle = "Template User";
    return view('user', compact('pageTitle'));
})->name('template.user');
Route::get('/template/profile', function() {
    $pageTitle = "Template Profile";
    return view('profile', compact('pageTitle'));
})->name('template.profile');

Route::post('/logout', 'Auth\LoginController@logout')->name('logout');
Route::get('/login', 'Auth\LoginController@showLoginForm')->name('login');
Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

Route::get('/password-email', function() {
    return 'password email';
})->name('password.email');
Route::get('/password-request', function() {
    return 'password request';
})->name('password.request');

// auth
Route::get('/register', [RegisterController::class, 'index'])->name('register');
Route::get('register/otp', [RegisterController::class, 'otpForm'])->name('register.otp');
Route::post('otp/whatsapp/validate', [RegisterController::class, 'submitOtpWhatsapp']);
Route::post('register/otp/{email}', [RegisterController::class, 'submitOtp'])->name('register.otp.store');
Route::post('/register', [RegisterController::class, 'submit'])->name('register.store');
Route::post('otp/send', [RegisterController::class, 'sendOtp'])->name('otp.send');

Route::middleware('auth')->group(function () {
    // CATEGORY
    Route::get('categories/ajax', [CategoryController::class, 'ajax'])->name('categories.ajax');
    Route::get('categories/change-status/{id}/{status}', [CategoryController::class, 'updateStatus']);
    Route::resource('categories', CategoryController::class);

    // USERS
    Route::get('users/{status}', [UserController::class, 'index'])->name('users.index');
    Route::get('users/show/{id}', [UserController::class, 'show'])->name('users.show');
    Route::get('users/transactions/{id}', [UserController::class, 'transactions'])->name('users.transactions');
    Route::get('users/logins/{id}', [UserController::class, 'logins'])->name('users.logins');
    Route::get('users/notification-form/{id}', [UserController::class, 'notificationForm'])->name('users.notification-form');
    Route::get('users/notification/{id}', [UserController::class, 'notification'])->name('users.notification');
    Route::get('users/login/{id}', [UserController::class, 'login'])->name('users.login');
    Route::get('users/balance/{id}/{type}', [UserController::class, 'balanceForm'])->name('users.add-deduct-balance');
    Route::post('users/balance/{id}', [UserController::class, 'addDeductBalance'])->name('users.balance-add-deduct');
    Route::get('users/ban/{id}', [UserController::class, 'banUserForm'])->name('users.ban');
    Route::get('users/unban/{id}', [UserController::class, 'unbanUser'])->name('users.unban');
    Route::post('users/do-ban/{id}', [UserController::class, 'banUser'])->name('users.do-ban');
    Route::get('users/update-balance/{id}', [UserController::class, 'updateBalance'])->name('users.update-balance');
    Route::put('users/{id}', [UserController::class, 'update'])->name('users.update');
    Route::get('users/ajax/{status}', [UserController::class, 'ajax'])->name('users.ajax');
    Route::post('users/send-manual-notification/{id}', [UserController::class, 'sendNotification'])->name('users.send-notification');
    Route::get('users/ajax-transaction/{id}', [UserController::class, 'ajaxTransaction'])->name('users.ajax-transaction');
    Route::get('users/ajax-login-history/{id}', [UserController::class, 'ajaxLoginHistory'])->name('users.ajax-login-history');
    Route::get('users/ajax-notification/{id}', [UserController::class, 'ajaxNotification'])->name('users.ajax-notification');
    Route::get('/users/transaction/status/{trx}', [UserController::class, 'getStatusTransaction'])->name('users.transaction.get-status');

    // Deposit
    Route::get('deposit/ajax', [DepositController::class, 'ajax'])->name('deposit.ajax');
    Route::get('deposit/image', [DepositController::class, 'showDetailImage'])->name('deposit.see-image');
    Route::resource('deposit', DepositController::class);
    Route::get('deposit/update-deposit-rule/{gateawayId}', [DepositController::class, 'updateDepositRule'])->name('deposit.update-deposit-rule');
    Route::get('deposit/do-confirm/{trx}', [DepositController::class, 'doConfirmDeposit'])->name('deposit.do-confirm');
    Route::get('deposit/users/{trx}', [DepositController::class, 'detailUserDeposit'])->name('deposit.user.confirm');
    Route::get('deposit/payment-instruction/{trx}', [DepositController::class, 'paymentInstruction'])->name('deposit.payment-instruction');
    Route::get('deposit/decline-form/{trx}', [DepositController::class, 'declineForm'])->name('deposit.decline-form');
    Route::post('deposit/decline/{trx}', [DepositController::class, 'submitDeclineDeposit'])->name('deposit.submit-decline');
    Route::get('deposit/users/confirm/{trx}', [DepositController::class, 'confirmManualPayment'])->name('deposit.user-proof-form');
    Route::post('deposit/confirm-payment/{trx}', [DepositController::class, 'confirmPayment'])->name('deposit.confirm-payment');

    // Payment Gateaway
    Route::prefix('payment-gateaway')->group(function () {
        Route::get('/{type}', [PaymentGateawaySettingController::class, 'index'])->name('payment-gateaway.index');
        Route::delete('/delete/{id}', [PaymentGateawaySettingController::class, 'destroy'])->name('payment-gateaway.destroy');
        Route::get('/create/data', [PaymentGateawaySettingController::class, 'create'])->name('payment-gateaway.create');
        Route::post('/store/data', [PaymentGateawaySettingController::class, 'store'])->name('payment-gateaway.store');
        Route::post('/update/data', [PaymentGateawaySettingController::class, 'update'])->name('payment-gateaway.update');
        Route::get('/ajax/{type}', [PaymentGateawaySettingController::class, 'ajax'])->name('payment-gateaway-ajax');
        Route::get('/edit/{id}/{type}', [PaymentGateawaySettingController::class, 'edit'])->name('payment-gateaway-edit');
        Route::get('/user-data-form/{id}', [PaymentGateawaySettingController::class, 'userDataForm'])->name('payment_gateaway.user-data-form');
        Route::get('/user-data/{id}', [PaymentGateawaySettingController::class, 'userData'])->name('payment-gateaway.user-data');
        Route::post('/user-data/{id}', [PaymentGateawaySettingController::class, 'storeUserData'])->name('payment-gateaway.user-data.store');
        Route::get('user-data/delete/{key}', [PaymentGateawaySettingController::class, 'deleteUserData'])->name('payment-gateaway.user-data.delete');
        Route::get('/user-data/{id}/{key}/edit', [PaymentGateawaySettingController::class, 'userDataEdit'])->name('payment-gateaway.user-data.edit');

        // automatic
        Route::prefix('automatic')->group(function () {
            Route::get('/ajax', [PaymentGateawaySettingController::class, 'ajaxAutomatic'])->name('payment-gateaway.automatic.ajax');
            Route::get('/update', [PaymentGateawaySettingController::class, 'updateAutomatic'])->name('payment-gateaway.automatic.update');
            Route::get('/edit/{id}', [PaymentGateawaySettingController::class, 'editAutomatic'])->name('payment-gateaway.automatic.edit');
            Route::post('/store/{id}', [PaymentGateawaySettingController::class, 'storeAutomatic'])->name('payment-gateaway.automatic.store');
            Route::get('/update-currency/{id}', [PaymentGateawaySettingController::class, 'updateCurrency'])->name('payment-gateaway.automatic.update-currency');
            Route::post('/add-new-currency-form/{id}', [PaymentGateawaySettingController::class, 'addCurrencyForm'])->name('payment-gateaway.automatic.add-new-currency');
            Route::get('/init-detail-currency/{id}', [PaymentGateawaySettingController::class, 'initDetailCurrency'])->name('payment-gateaway.automatic.init-detail-currency');
            Route::get('/channel-tripay/{id}', [PaymentGateawaySettingController::class, 'generateTripayChannel'])->name('payment-gateaway.automatic.tripay-channel');
            Route::get('/local-channel-tripay/{id}', [PaymentGateawaySettingController::class, 'localChannelTripay'])->name('payment-gateaway.automatic.local-tripay-channel');
        });
    });
});

