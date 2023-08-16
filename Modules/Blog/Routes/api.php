<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Modules\Blog\Http\Controllers\BlogController;
use Modules\Blog\Http\Controllers\BlogGroupController;

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

//Route::middleware('auth:api')->get('/blog', function (Request $request) {
//    return $request->user();
//});


Route::prefix('blog')->group(function () {
    Route::prefix('v1')->group(function () {
        Route::get('getBlog', [BlogController::class, 'getBlog']);
        Route::post('StoreBlog', [BlogController::class, 'StoreBlog']);
        Route::get('UpdateBlog/{id}', [BlogController::class, 'UpdateBlog']);
        Route::post('UpdateBlog', [BlogController::class, 'StoreUpdateBlog']);
        Route::get('/removeBlog/{id}', [BlogController::class, 'removeBlog']);

    });
});

Route::prefix('blogGroup')->group(function () {
    Route::prefix('v1')->group(function () {
        Route::get('getBlogGroup', [BlogGroupController::class, 'getBlogGroup']);
        Route::post('StoreBlogGroup', [BlogGroupController::class, 'StoreBlogGroup']);
        Route::get('UpdateBlogGroup/{id}', [BlogGroupController::class, 'UpdateBlogGroup']);
        Route::post('UpdateBlogGroup', [BlogGroupController::class, 'StoreUpdateBlogGroup']);
        Route::get('/removeBlogGroup/{id}', [BlogGroupController::class, 'removeBlogGroup']);

    });
});
