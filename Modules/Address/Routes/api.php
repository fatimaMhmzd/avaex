<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Modules\Address\Http\Controllers\AddressController;
use Modules\Address\Http\Controllers\CityController;
use Modules\Address\Http\Controllers\CountryController;
use Modules\Address\Http\Controllers\ProvinceController;
use Modules\Address\Http\Controllers\AreaController;

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

Route::prefix('address')->group(function () {
    Route::prefix('v1')->group(function () {
        Route::prefix('country')->group(function () {
            Route::post('/add', [CountryController::class, 'add']);
            Route::post('/update', [CountryController::class, 'update']);
            Route::get('/delete/{id}', [CountryController::class, 'delete']);
            Route::get('/all', [CountryController::class, 'all']);
            Route::get('/show/{id}', [CountryController::class, 'show']);

        });
        Route::prefix('province')->group(function () {
            Route::post('/add', [ProvinceController::class, 'add']);
            Route::post('/update', [ProvinceController::class, 'update']);
            Route::get('/delete/{id}', [ProvinceController::class, 'delete']);
            Route::get('/all', [ProvinceController::class, 'all']);
            Route::get('/show/{id}', [ProvinceController::class, 'show']);
        });
        Route::prefix('city')->group(function () {
            Route::post('/add', [CityController::class, 'add']);
            Route::post('/update', [CityController::class, 'update']);
            Route::get('/delete/{id}', [CityController::class, 'delete']);
            Route::get('/all/{numberpage}/{numberitems}/{countryId}/{provinceId}/{srcinput?}', [CityController::class, 'all']);
            Route::get('/show/{id}', [CityController::class, 'show']);
        });
        Route::prefix('area')->group(function () {
            Route::post('/add', [AreaController::class, 'add']);
            Route::post('/update', [AreaController::class, 'update']);
            Route::get('/delete/{id}', [AreaController::class, 'delete']);
            Route::get('/all/{numberpage}/{numberitems}', [AreaController::class, 'all']);
            Route::get('/show/{id}', [AreaController::class, 'show']);
        });

        Route::group(['middleware'=>['auth:sanctum']], function () {
        Route::post('/addAddress', [AddressController::class, 'addAddress']);
        Route::post('/updateAddress', [AddressController::class, 'updateAddress']);
        Route::get('/deleteAddress/{id}', [AddressController::class, 'deleteAddress']);
        Route::get('/showAddress/{numberpage}/{srcinput?}', [AddressController::class, 'showAddress']);
        Route::get('/search/{type}/{srcinput?}', [AddressController::class, 'search']);
        Route::get('/userAdress/{id}', [AddressController::class, 'userAdress']);
        Route::get('/defult/{type}', [AddressController::class, 'defultAdress']);
        });

        /*Route::get('/test', [CountryController::class, 'test']);*/

    });

});
