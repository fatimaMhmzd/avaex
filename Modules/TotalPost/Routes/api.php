<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Modules\TotalPost\Http\Controllers\TotalPostController;
use Modules\TotalPost\Http\Controllers\TotalPostAgentController;
use Modules\TotalPost\Http\Controllers\TotalPostAdminController;

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

Route::prefix('totalpost')->group(function () {
    Route::prefix('v1')->group(function () {
        Route::get('/mainFactor/{id}', [TotalPostController::class, 'mainFactor']);
        Route::get('/mainFactor2/{id}', [TotalPostController::class, 'mainFactor2']);
        Route::get('/deleteFactor/{id}', [TotalPostController::class, 'deleteFactor']);

        Route::get('/detail/{id}', [TotalPostController::class, 'detail']);
        Route::get('/detailByBarname/{id}', [TotalPostController::class, 'detailByBarname']);
        Route::get('/agent/show/exel/{id}', [TotalPostAgentController::class, 'showExel']);
        Route::group(['middleware'=>['auth:sanctum']], function () {
            Route::prefix('agent')->group(function () {
                Route::get('/show', [TotalPostAgentController::class, 'show']);
                Route::get('/count', [TotalPostAgentController::class, 'count']);
            });



            Route::get('/preview/{id}', [TotalPostController::class, 'preview']);
            Route::get('/update', [TotalPostController::class, 'update']);
            Route::get('/groupUpdate', [TotalPostController::class, 'groupUpdate']);
            Route::post('/mypart', [TotalPostController::class, 'mypart']);

            Route::post('/factor', [TotalPostController::class, 'factor']);
            Route::get('/show', [TotalPostController::class, 'show']);
            Route::get('/payment/{type}/{totalPostId}', [TotalPostController::class, 'payment']);

        });
        Route::get('/mypart/exel/{id}', [TotalPostController::class, 'mypartExel']);
        Route::prefix('admin')->group(function () {
            Route::get('/show', [TotalPostAdminController::class, 'show']);
            Route::get('/show/exel', [TotalPostAdminController::class, 'showExel']);
            Route::get('/remember', [TotalPostAdminController::class, 'remember']);
            Route::get('/province', [TotalPostAdminController::class, 'province']);
            Route::get('/cities/{provinceId}', [TotalPostAdminController::class, 'cities']);
            Route::get('/sendedByCity/{cityId}', [TotalPostAdminController::class, 'sendedByCity']);
            Route::post('/mypart', [TotalPostAdminController::class, 'mypart']);
            Route::get('/showAgent', [TotalPostAdminController::class, 'showAgent']);
            Route::get('/find/{data}', [TotalPostAdminController::class, 'find']);

        });
        Route::prefix('zarinpal')->group(function () {
            Route::get('/request', [TotalPostController::class, 'request']);
            Route::get('/verification/{userId}/{amount}/{totalId}/{walletId}/{dc}', [TotalPostController::class, 'verification']);
        });

        Route::get('/check/{number}', [TotalPostController::class, 'check']);
        Route::get('/checkBarname/{id}', [TotalPostController::class, 'checkBarname']);
    });

});
