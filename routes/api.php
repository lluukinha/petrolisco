<?php

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Flags\FlagsController;
use App\Http\Controllers\FuelTypes\FuelTypesController;
use App\Http\Controllers\GasStationPriceDetails\GasStationPriceDetailsController;
use App\Http\Controllers\GasStationPrices\GasStationPricesController;
use App\Http\Controllers\GasStations\GasStationsController;
use Illuminate\Support\Facades\Route;

Route::middleware('api')->prefix('auth')->group(function () {
    Route::post('login', [AuthController::class, 'login'])->name('auth.login');
    Route::post('logout', [AuthController::class, 'logout'])->name('auth.logout');
    Route::post('refresh', [AuthController::class, 'refresh'])->name('auth.refresh');
    Route::get('me', [AuthController::class, 'me'])->name('auth.me');
});

Route::group(['prefix' => 'flags'], function($router) {
    Route::get('/', [FlagsController::class, 'index']);
    Route::get('/{id}', [FlagsController::class, 'show']);
    Route::post('/create', [FlagsController::class, 'create']);
    Route::put('/{id}', [FlagsController::class, 'update']);
});

Route::group(['prefix' => 'fuel-types'], function($router) {
    Route::get('/', [FuelTypesController::class, 'index']);
    Route::get('/{id}', [FuelTypesController::class, 'show']);
    Route::post('/create', [FuelTypesController::class, 'create']);
    Route::put('/{id}', [FuelTypesController::class, 'update']);
});

Route::resource('gas-stations', GasStationsController::class);
Route::resource('gas-station-prices', GasStationPricesController::class);
Route::resource('gas-station-price-details', GasStationPriceDetailsController::class);

Route::get('/', function() {
    return ['text' => 'get endpoint'];
});
