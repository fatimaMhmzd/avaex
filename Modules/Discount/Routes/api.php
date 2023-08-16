<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Modules\Discount\Http\Controllers\DiscountController;
use Modules\Discount\Http\Controllers\DiscountUserController;

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


Route::prefix('discount')->group(function () {
    Route::prefix('v1')->group(function () {

        Route::post('/add', [DiscountController::class, 'add']);
        Route::get('/all', [DiscountController::class, 'all']);

        Route::get('/delete/{id}', [DiscountController::class, 'delete']);
        Route::prefix('customer')->group(function () {
            Route::group(['middleware'=>['auth:sanctum']], function () {
            Route::get('/check', [DiscountController::class, 'checkDiscount']);
            Route::get('/show', [DiscountController::class, 'userDiscount']);
            });
            Route::get('/setAll', [DiscountUserController::class, 'setDiscountAll']);
            Route::get('/updateAll', [DiscountUserController::class, 'updateDiscountAll']);
            Route::get('/setGroup', [DiscountUserController::class, 'setGroupDiscount']);
            Route::get('/all', [DiscountUserController::class, 'all']);
            Route::get('/delete/{id}', [DiscountUserController::class, 'delete']);

        });


    });

});