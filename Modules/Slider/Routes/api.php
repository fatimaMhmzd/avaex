<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Modules\Slider\Http\Controllers\SliderController;

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


Route::prefix('slider')->group(function () {
    Route::prefix('v1')->group(function () {
        Route::post('/addslider', [SliderController::class, 'addSlider']);
        Route::post('/updateslider', [SliderController::class, 'updateSlider']);
        Route::get('/deleteslider/{id}', [SliderController::class, 'deleteSlider']);
        Route::get('/showsliderpage/{pageId}', [SliderController::class, 'showsliderpage']);





    });
});
