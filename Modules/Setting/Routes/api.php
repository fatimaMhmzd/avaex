<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Modules\Setting\Http\Controllers\SettingController;
use Modules\Setting\Http\Controllers\FaqController;
use Modules\Setting\Http\Controllers\TicketController;
use Modules\Setting\Http\Controllers\ComplainController;
use Modules\Setting\Http\Controllers\ContactUsController;
use Modules\Setting\Http\Controllers\CoWorkerController;
use Modules\Setting\Http\Controllers\NetWorkController;

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

Route::prefix('setting')->group(function () {
    Route::prefix('v1')->group(function () {
        Route::prefix('faq')->group(function () {
            Route::post('/add', [FaqController::class, 'add']);
            Route::post('/update', [FaqController::class, 'update']);
            Route::get('/delete/{id}', [FaqController::class, 'delete']);
            Route::get('/all', [FaqController::class, 'all']);
            Route::get('/show/{id}', [FaqController::class, 'show']);


        });
        Route::group(['middleware'=>['auth:sanctum']], function () {
        Route::prefix('ticket')->group(function () {
            Route::post('/add', [TicketController::class, 'add']);
            Route::post('/update', [TicketController::class, 'update']);
            Route::get('/delete/{id}', [TicketController::class, 'delete']);
            Route::get('/all', [TicketController::class, 'all']);
            Route::get('/show/{id}', [TicketController::class, 'show']);
            Route::get('/all', [TicketController::class, 'all']);
        });
        });
        Route::get('ticket/allDashboard', [TicketController::class, 'allDashboard']);
        Route::post('ticket/changeHandling', [TicketController::class, 'changeHandlingunit']);

        Route::prefix('complain')->group(function () {
            Route::post('/add', [ComplainController::class, 'add']);
            Route::post('/update', [ComplainController::class, 'update']);
            Route::get('/delete/{id}', [ComplainController::class, 'delete']);
            Route::get('/all', [ComplainController::class, 'all']);
            Route::get('/show/{id}', [ComplainController::class, 'show']);



        });
        Route::prefix('contactus')->group(function () {
            Route::post('/add', [ContactUsController::class, 'add']);
            Route::post('/update', [ContactUsController::class, 'update']);
            Route::get('/delete/{id}', [ContactUsController::class, 'delete']);
            Route::get('/all', [ContactUsController::class, 'all']);
            Route::get('/show/{id}', [ContactUsController::class, 'show']);



        });
        Route::prefix('coworker')->group(function () {
            Route::post('/add', [CoWorkerController::class, 'add']);
            Route::post('/update', [CoWorkerController::class, 'update']);
            Route::get('/delete/{id}', [CoWorkerController::class, 'delete']);
            Route::get('/all', [CoWorkerController::class, 'all']);
            Route::get('/show/{id}', [CoWorkerController::class, 'show']);



        });
        Route::prefix('network')->group(function () {
            Route::post('/add', [NetWorkController::class, 'add']);
            Route::post('/update', [NetWorkController::class, 'update']);
            Route::get('/delete/{id}', [NetWorkController::class, 'delete']);
            Route::get('/all', [NetWorkController::class, 'all']);
            Route::get('/show/{id}', [NetWorkController::class, 'show']);



        });
        Route::prefix('setting')->group(function () {
            Route::post('/update', [SettingController::class, 'update']);
            Route::get('/all', [SettingController::class, 'all']);
        });

        Route::get('/start', [SettingController::class, 'start']);
        Route::get('/test', [SettingController::class, 'test']);


    });
});
