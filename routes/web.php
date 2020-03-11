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

    ///products
    Route::group(['prefix' => 'product', 'as' => 'product.'], function() {
        Route::get('/', 'ProductController@index')->name('index');
        Route::post('/store', 'ProductController@store')->name('store');
        Route::post('/{product}/images', 'ProductController@storeImages')->name('store.images');
        Route::get('/getproduct', 'ProductController@getProducts')->name('get');
        Route::get('/{product}/publish', 'ProductController@changePublishStatus');
    });

    ////categories
    Route::group(['prefix' => 'categories', 'as' => 'categories.'], function() {
        Route::post('/store', 'CategoryController@store');
        Route::post('/edit', 'CategoryController@edit');
        Route::post('remove', 'CategoryController@remove');
    });
});