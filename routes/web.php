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
    ////Routes for product
    Route::group(['prefix' => 'product', 'as' => 'product.'], function() {
        Route::get('/', 'ProductController@index')->name('products.index');
        Route::post('/store', 'ProductController@store')->name('products.store');
        Route::get('/getproduct', 'ProductController@getProducts')->name('products.get');
        Route::get('/{product}/publish', 'ProductController@changePublishStatus');
        Route::post('/edit', 'ProductController@edit');
        Route::post('/remove', 'ProductController@remove');
    });

    /////Rotes for category
    Route::group(['prefix' => 'category', 'as' => 'category.'], function() {
        Route::post('/store', 'CategoryController@store');
    });
});