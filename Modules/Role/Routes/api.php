<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Modules\Role\Http\Controllers\RoleController;

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

/*Route::middleware('auth:api')->get('/role', function (Request $request) {
    return $request->user();
});*/
Route::prefix('role')->group(function () {
    Route::prefix('v1')->group(function () {
//        Route::group(['middleware'=>['auth:sanctum']], function () {
//            Route::group(['middleware'=>'checkAdmin'], function () {
                Route::prefix('admin')->group(function () {
                    Route::get('/showrole/{srcinput?}', [RoleController::class, 'showRole']);
                    Route::get('/deleterole/{id}', [RoleController::class, 'deleteRole']);
                    Route::post('/updaterole', [RoleController::class, 'updateRole']);
                    Route::post('/addrole', [RoleController::class, 'addRole']);
                });


            });


//        });

//    });
});
