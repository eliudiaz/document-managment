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
// Authentication routes...
Route::get('auth/login', ['as' => 'login', 'uses' => 'Auth\LoginController@showLoginForm']);
Route::post('auth/login', ['as' => 'doLogin', 'uses' => 'Auth\LoginController@login']);
Route::get('auth/logout', 'Auth\LoginController@logout');

Route::group(['middleware' => 'auth'], function () {
    Route::get('/', 'EditorController@index');

    Route::resource('/documents', 'EditorController');

    Route::get('/api/documents/{id}/download', 'EditorController@getFile');

    Route::get('/api/documents', 'EditorController@all');
    Route::delete('/api/documents/{id}', 'EditorController@destroy');
});
