<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Modules\InternalPost\Http\Controllers\InternalPostController;
use Modules\InternalPost\Http\Controllers\InternalPostAgentController;


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

Route::prefix('internalpost')->group(function () {
    Route::prefix('v1')->group(function () {
        Route::post('/mahexRate', [InternalPostController::class, 'mahexRate']);
        Route::post('/postRate', [InternalPostController::class, 'postRate']);
        Route::group(['middleware' => ['auth:sanctum']], function () {
            Route::post('/add', [InternalPostController::class, 'add']);
            Route::post('/bulkadd', [InternalPostController::class, 'bulkAdd']);
            Route::prefix('agent')->group(function () {
                Route::post('/add', [InternalPostAgentController::class, 'add']);
                Route::post('/bulkadd', [InternalPostAgentController::class, 'bulkAdd']);

            });
        });


    });
});
