<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Modules\HeavyPost\Http\Controllers\HeavyPostController;

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

Route::prefix('Heavypost')->group(function () {
    Route::prefix('v1')->group(function () {

        Route::Post('/addVehicles', [HeavyPostController::class, 'addVehicles']);
        Route::Post('/updateVehicles', [HeavyPostController::class, 'updateVehicles']);
        Route::get('/deleteVehicles/{id}', [HeavyPostController::class, 'deleteVehicles']);


        Route::Post('/addOptionVehicles', [HeavyPostController::class, 'addOptionVehicles']);
        Route::Post('/updateOptionVehicles', [HeavyPostController::class, 'updateOptionVehicles']);
        Route::get('/deleteOptionVehicles/{id}', [HeavyPostController::class, 'deleteOptionVehicles']);

        Route::Post('/addHeavyPost', [HeavyPostController::class, 'addHeavyPost']);
        Route::Post('/HeavyPostUpdateItem', [HeavyPostController::class, 'HeavyPostUpdateItem']);
        Route::Post('/addItemHeavyOrder', [HeavyPostController::class, 'addItemHeavyOrder']);
        Route::get('/deleteOptionVehicles/{id}', [HeavyPostController::class, 'deleteOptionVehicles']);




    });
});
