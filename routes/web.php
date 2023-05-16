<?php

use App\Http\Controllers\Auth\RegisterController;
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
Route::get('/dashboard', function() {
    $pageTitle = "Dashboard";
    return view('dashboard', compact('pageTitle'));
})->name('dashboard');

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

