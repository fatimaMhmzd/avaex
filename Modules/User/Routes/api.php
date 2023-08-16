<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Modules\User\Http\Controllers\UserAdminController;
use Modules\User\Http\Controllers\UserController;

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




Route::prefix('user')->group(function () {
    Route::prefix('v1')->group(function () {


        Route::post('/register', [UserController::class, 'register']);
        Route::post('/code', [UserController::class, 'code']);
        Route::post('/login', [UserController::class, 'login']);
        Route::post('/forgetpassword', [UserController::class, 'forgetPassword']);

        Route::post('/loginagent', [UserController::class, 'loginAgent']);

        Route::group(['middleware' => ['auth:sanctum']], function () {
            Route::get('/loginAgentWithToken', [UserController::class, 'loginAgentWithToken']);
        });
        Route::post('/loginadmin', [UserController::class, 'loginAdmin']);

        Route::group(['middleware' => ['auth:sanctum']], function () {
            Route::get('/loginAdminWithToken', [UserController::class, 'loginAdminWithToken']);
        });


        Route::group(['middleware' => ['auth:sanctum']], function () {

            Route::post('/updateuser', [UserController::class, 'updateUser']);
            Route::get('/showuser', [UserController::class, 'showUser']);
            Route::post('/updatpassword', [UserController::class, 'updatPassword']);
        });
        Route::get('/test1', [UserController::class, 'test1']);


//            Route::group(['middleware'=>'checkAdmin'], function () {
        Route::prefix('admin')->group(function () {
            Route::get('/showuser/{numberpage}/{numberitems}/{srcinput?}', [UserAdminController::class, 'showUser']);
            Route::get('/showuser/exel/{srcinput?}', [UserAdminController::class, 'showUserExel']);
            Route::get('/update/{id}', [UserAdminController::class, 'update']);
            Route::post('/storeUpdate', [UserAdminController::class, 'storeUpdate']);
            Route::get('/delete/{id}', [UserAdminController::class, 'deleteUser']);

            Route::post('/add', [UserAdminController::class, 'add']);

        });


//                });


//            });


    });
});

