<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Modules\DiscountCode\Http\Controllers\DiscountCodeController;

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


Route::prefix('discountCode')->group(function () {

    Route::prefix('v1')->group(function () {
        Route::group(['middleware' => ['auth:sanctum']], function () {
            Route::get('getDiscountCode', [DiscountCodeController::class, 'getDiscountCode']);
            Route::post('StoreDiscountCode', [DiscountCodeController::class, 'StoreDiscountCode']);
            Route::get('UpdateDiscountCode/{id}', [DiscountCodeController::class, 'UpdateDiscountCode']);
            Route::post('UpdateDiscountCode', [DiscountCodeController::class, 'StoreUpdateDiscountCode']);
            Route::get('/removeDiscountCode/{id}', [DiscountCodeController::class, 'removeDiscountCode']);
        });
    });


});
