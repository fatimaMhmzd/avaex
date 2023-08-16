<?php

use Illuminate\Http\Request;
use Modules\PostupServices\Http\Controllers\PostupServicesController;

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

Route::prefix('postupservices')->group(function () {
    Route::prefix('v1')->group(function () {
    Route::prefix('packaging')->group(function () {

        Route::get('/list', [PostupServicesController::class, 'listPackaging']);

    });
    });
});

