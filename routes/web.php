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

Auth::routes();

Route::group(['middleware' => ['auth', 'staff']], function(){
    Route::get('/', 'HomeController@index');
});

Route::get('/admin', 'AdminController@index')->middleware('auth');

Route::post('/admin', 'AdminController@create');

Route::get('/user', 'UserEditController@index');