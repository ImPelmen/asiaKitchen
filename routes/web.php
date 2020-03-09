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

Route::get('/', function () {
    return view('welcome');
});


Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');


Route::middleware(['auth', 'admin'])->namespace('Admin')->prefix('admin')->group(function () {
    Route::get('/product', 'ProductController@index')->name('products.index');
    Route::post('/product/store', 'ProductController@store')->name('products.store');
    Route::post('/product/{product}/images', 'ProductController@storeImages')->name('products.store.images');
    Route::get('/product/getproduct', 'ProductController@getProducts')->name('products.get');
    Route::get('/product/{product}/publish', 'ProductController@changePublishStatus');
});