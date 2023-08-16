<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

use Illuminate\Support\Facades\Route;
use Modules\PostupServices\Http\Controllers\PostupServicesController;

Route::prefix('postupservices')->group(function() {
    Route::get('/', 'PostupServicesController@index');
});


Route::group(['prefix' => 'postupServices'] , function (){
   Route::group(['prefix' => 'size'], function (){
      Route::get('/add', [PostupServicesController::class, 'createSize'])->name('createSize');
      Route::post('/store', [PostupServicesController::class, 'storeSize'])->name('storeSize');
      Route::get('/list', [PostupServicesController::class, 'listSize'])->name('listSize');
      Route::get('/ajax', [PostupServicesController::class, 'ajaxSize'])->name('ajaxSize');
      Route::get('/delete/{id}', [PostupServicesController::class, 'deleteSize'])->name('deleteSize');
      Route::get('/update/{id}', [PostupServicesController::class, 'updateSize'])->name('updateSize');
      Route::post('/storeUpdate/{id}', [PostupServicesController::class, 'storeUpdateSize'])->name('storeUpdateSize');
   });
   Route::group(['prefix' => 'packaging'], function (){
       Route::get('/add', [PostupServicesController::class, 'createPackaging'])->name('createPackaging');
       Route::post('/store', [PostupServicesController::class, 'storePackaging'])->name('storePackaging');
       Route::get('/list', [PostupServicesController::class, 'listPackaging'])->name('listPackaging');
       Route::get('/ajax', [PostupServicesController::class, 'ajaxPackaging'])->name('ajaxPackaging');
       Route::get('/delete/{id}', [PostupServicesController::class, 'deletePackaging'])->name('deletePackaging');
       Route::get('/update/{id}', [PostupServicesController::class, 'updatePackaging'])->name('updatePackaging');
       Route::post('/storeUpdate/{id}', [PostupServicesController::class, 'storeUpdatePackaging'])->name('storeUpdatePackaging');
   });
   Route::group(['prefix' => 'price'], function (){
       Route::get('/add', [PostupServicesController::class, 'createPrice'])->name('createPrice');
       Route::post('/store', [PostupServicesController::class, 'storePrice'])->name('storePrice');
       Route::get('/list', [PostupServicesController::class, 'listPrice'])->name('listPrice');
       Route::get('/ajax', [PostupServicesController::class, 'ajaxPrice'])->name('ajaxPrice');
       Route::get('/delete/{id}', [PostupServicesController::class, 'deletePrice'])->name('deletePrice');
       Route::get('/update/{id}', [PostupServicesController::class, 'updatePrice'])->name('updatePrice');
       Route::post('/storeUpdate/{id}', [PostupServicesController::class, 'storeUpdatePrice'])->name('storeUpdatePrice');
   });
   Route::group(['prefix' => 'insurance'], function (){
       Route::get('/add', [PostupServicesController::class, 'createInsurance'])->name('createInsurance');
       Route::post('/store', [PostupServicesController::class, 'storeInsurance'])->name('storeInsurance');
       Route::get('/list', [PostupServicesController::class, 'listInsurance'])->name('listInsurance');
       Route::get('/ajax', [PostupServicesController::class, 'ajaxInsurance'])->name('ajaxInsurance');
       Route::get('/delete/{id}', [PostupServicesController::class, 'deleteInsurance'])->name('deleteInsurance');
       Route::get('/update/{id}', [PostupServicesController::class, 'updateInsurance'])->name('updateInsurance');
       Route::post('/storeUpdate/{id}', [PostupServicesController::class, 'storeUpdateInsurance'])->name('storeUpdateInsurance');
   });
});
