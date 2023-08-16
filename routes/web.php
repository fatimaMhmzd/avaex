<?php

use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Route;

use Modules\Address\Http\Controllers\AddressController;
use Modules\TotalPost\Http\Controllers\TotalPostController;
use App\Http\Controllers\NewprintController;
use Illuminate\Support\Facades\Auth;

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
Route::get('/postTest', [AddressController::class, 'postTest']);


Route::get('/', function () {
    return view('welcome');
});


Route::get('/printaaaa', [AddressController::class, 'test']);
Route::get('/printaaaaPreview', [AddressController::class, 'testPreview']);

Auth::routes();

Route::get('/rate', [AddressController::class, 'rate']);
Route::get('/test1/{totalId}', [AddressController::class, 'test1']);
Route::get('/test2', [AddressController::class, 'test2']);
Route::get('/exeltest', [AddressController::class, 'exeltest']);
Route::get('/testAfter/{id}', [AddressController::class, 'testAfter']);
Route::get('/changeState/{id}', [AddressController::class, 'changeState']);
Route::get('/deleteAddress', [AddressController::class, 'deleteAddressa']);
Route::get('/importCity/{id}', [AddressController::class, 'importCity']);
Route::get('/createStore/{id}', [AddressController::class, 'createStore']);
Route::get('/deleteTest/{item}', [AddressController::class, 'deleteTest']);
Route::get('/PostPriceTest', [AddressController::class, 'PostPriceTest']);



Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::get('/print1',[NewprintController::class,'print1'])->name('print1');
Route::get('/print2',[NewprintController::class,'print2'])->name('print2');
Route::get('/print3',[NewprintController::class,'print3'])->name('print3');
Route::get('/print4',[NewprintController::class,'print4'])->name('print4');

Route::get('/printtest/{id}',[TotalPostController::class,'mainFactor']);








