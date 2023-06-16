<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\DepositApiController;
use App\Http\Controllers\Api\GeoLocationController;
use App\Http\Controllers\Api\PaymentGateawayApiController;
use App\Http\Controllers\Api\Payments\TripayController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// Auth
Route::post('login', [AuthController::class, 'login']);

Route::get('/countries', [GeoLocationController::class, 'getCountries']);
Route::get('/provinces', [GeoLocationController::class, 'getProvinces']);
Route::get('/regencies', [GeoLocationController::class, 'getRegencies']);

// Tripay
Route::prefix('tripay')->group(function () {
    Route::post('/', [TripayController::class, 'callback']);
    Route::get('create-transaction', [TripayController::class, 'createTransaction']);
});

Route::middleware('auth:sanctum')->group(function () {
    Route::get('deposit/update-rule/{gateawayId}', [DepositApiController::class, 'updateRule']);
    Route::resource('deposit', DepositApiController::class);
    Route::resource('payment-gateaway', PaymentGateawayApiController::class);
});

