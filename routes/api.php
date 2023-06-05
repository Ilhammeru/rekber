<?php

use App\Http\Controllers\Api\GeoLocationController;
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

Route::get('/countries', [GeoLocationController::class, 'getCountries']);
Route::get('/provinces', [GeoLocationController::class, 'getProvinces']);
Route::get('/regencies', [GeoLocationController::class, 'getRegencies']);

// Tripay
Route::prefix('tripay')->group(function () {
    Route::post('/', [TripayController::class, 'callback']);
    Route::get('create-transaction', [TripayController::class, 'createTransaction']);
});
