<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Modules\Wallet\Http\Controllers\WalletController;
use Modules\Wallet\Http\Controllers\AccountBankController;

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
Route::prefix('wallet')->group(function () {
    Route::prefix('v1')->group(function () {
        Route::get('/all/exel/{id}', [WalletController::class, 'allExel']);
        Route::get('/collector/exel/{id}', [WalletController::class, 'collectorExel']);
        Route::group(['middleware' => ['auth:sanctum']], function () {

            Route::get('/all', [WalletController::class, 'all']);

            Route::get('/collector', [WalletController::class, 'collector']);

            Route::get('/show/{id}', [WalletController::class, 'show']);
            Route::post('/transfer', [WalletController::class, 'transferCredit']);

//          Route::get('/request', [WalletController::class, 'request']);
        });
        Route::post('/transferAmount', [WalletController::class, 'transferAmount']);

        Route::prefix('admin')->group(function () {
            Route::get('/all', [WalletController::class, 'adminAll']);
            Route::get('/collector', [WalletController::class, 'adminCollector']);

        });

        Route::get('/dashboardService', [WalletController::class, 'dashboardService']);
        Route::get('/dashboardService/exel', [WalletController::class, 'dashboardServiceExel']);
        Route::post('/dashboardServicePayment', [WalletController::class, 'dashboardServicePayment']);
        Route::get('/avax', [WalletController::class, 'avax']);
        Route::get('/avax/exel', [WalletController::class, 'avaxExel']);


        Route::prefix('zarinpal')->group(function () {
            Route::get('/request', [WalletController::class, 'request']);
            Route::get('/verification/{userId}/{amount}/{id}', [WalletController::class, 'verification']);
        });

        Route::prefix('bank')->group(function () {
            Route::post('/add', [AccountBankController::class, 'add']);
            Route::post('/update', [AccountBankController::class, 'update']);
            Route::get('/delete/{id}', [AccountBankController::class, 'delete']);
            Route::get('/all', [AccountBankController::class, 'all']);
            Route::get('/show/{id}', [AccountBankController::class, 'show']);


        });

        /*Route::get('/test', [CountryController::class, 'test']);*/

    });

});
//Route::middleware('auth:api')->get('/wallet', function (Request $request) {
//    return $request->user();
//});
