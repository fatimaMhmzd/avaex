<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Modules\MyAccount\Http\Controllers\DamageController;
use Modules\MyAccount\Http\Controllers\MyAccountController;
use Modules\MyAccount\Http\Controllers\CODRequestController;

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

Route::prefix('myaccount')->group(function () {
    Route::prefix('v1')->group(function () {

        Route::group(['middleware' => ['auth:sanctum']], function () {
            Route::prefix('damage')->group(function () {
                Route::post('/add', [DamageController::class, 'add']);
                Route::post('/all', [DamageController::class, 'all']);

            });
            Route::prefix('codrequest')->group(function () {
                Route::post('/add', [CODRequestController::class, 'add']);
                Route::post('/all', [CODRequestController::class, 'all']);

            });


        });


    });

});
