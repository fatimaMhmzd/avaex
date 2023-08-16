<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Modules\Agent\Http\Controllers\AgentController;
use Modules\Agent\Http\Controllers\AgentAdminController;
use Modules\Agent\Http\Controllers\DriverController;

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

Route::prefix('agent')->group(function () {
    Route::prefix('v1')->group(function () {
        Route::prefix('admin')->group(function () {
            Route::post('/add', [AgentAdminController::class, 'add']);
            Route::get('/show/{id}', [AgentAdminController::class, 'show']);
            Route::get('/update/{id}', [AgentAdminController::class, 'update']);
            Route::get('/delete/{id}', [AgentAdminController::class, 'delete']);
            Route::post('/storeupdate', [AgentAdminController::class, 'storeUpdate']);
            Route::get('/all/{numberpage}/{numberitems}/{provinceId}/{cityId}/{srcinput?}', [AgentAdminController::class, 'all']);
            Route::get('/all/exel/{provinceId}/{cityId}/{srcinput?}', [AgentAdminController::class, 'allExel']);
            Route::get('/reserv/{numberpage}/{numberitems}/{provinceId}/{cityId}/{srcinput?}', [AgentAdminController::class, 'reservation']);
            Route::get('/reserv/exel/{provinceId}/{cityId}/{srcinput?}', [AgentAdminController::class, 'reservationExel']);

            Route::prefix('driver')->group(function () {
                Route::post('/add', [DriverController::class, 'add']);
                Route::post('/update', [DriverController::class, 'update']);
                Route::get('/delete/{id}', [DriverController::class, 'delete']);
                Route::get('/list/{agentId}', [DriverController::class, 'list']);
                Route::get('/single/{id}', [DriverController::class, 'single']);
            });
        });
        Route::group(['middleware' => ['auth:sanctum']], function () {
            Route::post('/add', [AgentController::class, 'add']);
            Route::get('/driver', [AgentController::class, 'driver']);
            Route::get('/setDriver/{driverId}/{itemId}', [AgentController::class, 'setDriver']);
            Route::get('/setGroupDriver/{driverId}/{itemId}', [AgentController::class, 'setGroupDriver']);
            Route::get('/setGroupDriverRemember/{itemId}', [AgentController::class, 'setGroupDriverRemember']);
        });
    });
});

