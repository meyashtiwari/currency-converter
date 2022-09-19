<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CurrencyController;

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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::middleware('auth:api')->group(function() {
    Route::get('/currency/{sCode}', 'App\Http\Controllers\CurrencyController@index');
    Route::get('/currency_new/{sCode}', [CurrencyController::class, 'handle_controller']);
    Route::get('/all-currencies', [CurrencyController::class, 'getAllCurrencies']);
    Route::get('/fetch_History', [CurrencyController::class, 'fetchHistory']);
    Route::get('/fetch_CurrencyMMA', [CurrencyController::class, 'fetchCurrencyMMA']);
    Route::get('/currency_nominal', [CurrencyController::class, 'fetchCurrencyNominal']);
});