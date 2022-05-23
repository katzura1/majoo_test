<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('product', 'API\ProductController@create');
Route::get('product/{id?}', 'API\ProductController@read');
Route::put('product/{id}', 'API\ProductController@update');
Route::delete('product/{id}', 'API\ProductController@delete');

Route::post('product_category', 'API\ProductCategoryController@create');
Route::get('product_category/{id?}', 'API\ProductCategoryController@read');
Route::put('product_category/{id}', 'API\ProductCategoryController@update');
Route::delete('product_category/{id}', 'API\ProductCategoryController@delete');

Route::post('admins', 'API\AdminController@create');
Route::post('login', 'API\AdminController@login');
Route::get('admins/{id?}', 'API\AdminController@read');
Route::put('admins/{id}', 'API\AdminController@update');
Route::put('admins/password/{id}', 'API\AdminController@update_password');
Route::delete('admins/{id}', 'API\AdminController@delete');
