<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Modules\Pages\Http\Controllers\PagesController;

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

Route::prefix('pages')->group(function () {
    Route::prefix('v1')->group(function () {
        Route::get('/pageditail/{id}', [PagesController::class, 'pageDitail']);
//        Route::group(['middleware'=>['auth:sanctum']], function () {
//            Route::group(['middleware'=>'checkAdmin'], function () {
                Route::prefix('admin')->group(function () {

        Route::post('/updatepage', [PagesController::class, 'updatePage']);
        Route::get('/deleteImage/{id}', [PagesController::class, 'deleteImage']);
        Route::get('/allpageditail', [PagesController::class, 'allPageDitail']);


                });


            });


//        });
//
//
//
//
//    });
});
