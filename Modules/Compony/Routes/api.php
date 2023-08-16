<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Modules\Compony\Http\Controllers\ComponyController;

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


Route::prefix('compony')->group(function () {
    Route::prefix('v1')->group(function () {
//        Route::group(['middleware'=>'auth:sanctum'], function () {
        Route::prefix('admin')->group(function () {
            Route::post('/add', [ComponyController::class, 'addCompony']);
            Route::post('/update', [ComponyController::class, 'updateCompony']);
            Route::get('/delete/{id}', [ComponyController::class, 'deleteCompony']);
            Route::get('/show', [ComponyController::class, 'showCompony']);

//        });


        });
    });
});
Route::prefix('componyType')->group(function () {
    Route::prefix('v1')->group(function () {
//        Route::group(['middleware'=>'auth:sanctum'], function () {
        Route::prefix('admin')->group(function () {
            Route::post('/add', [ComponyController::class, 'addComponyType']);
            Route::post('/update', [ComponyController::class, 'updateComponyType']);
            Route::get('/delete/{id}', [ComponyController::class, 'deleteComponyType']);
            Route::get('/show', [ComponyController::class, 'showComponyType']);

//        });


        });
    });
});
Route::prefix('componyservices')->group(function () {
    Route::prefix('v1')->group(function () {
//        Route::group(['middleware'=>'auth:sanctum'], function () {
        Route::prefix('admin')->group(function () {
            Route::post('/add', [ComponyController::class, 'addComponyServices']);
            Route::post('/update', [ComponyController::class, 'updateComponyServices']);
            Route::get('/delete/{id}', [ComponyController::class, 'deleteComponyServices']);
            Route::get('/show', [ComponyController::class, 'showComponyServices']);
            Route::get('/all', [ComponyController::class, 'all']);
            Route::get('/allBulk', [ComponyController::class, 'allBulk']);

//        });


        });
    });
});
