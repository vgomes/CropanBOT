<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/
Route::group(['middleware' => 'auth'], function () {
    Route::get('/', ['as' => 'pages.index', 'uses' => 'Pages@index']);
    Route::get('/history', ['as' => 'pages.history', 'uses' => 'Pages@history']);
    Route::get('/stats', ['as' => 'pages.stats', 'uses' => 'Pages@stats']);
});

Route::get('/login/twitter', ['as' => 'login.twitter', 'uses' => 'Pages@TwitterLogin']);
Route::get('/auth/twitter', ['as' => 'auth.twitter', 'uses' => 'Pages@TwitterAuth']);

Route::get('/login', ['as' => 'login', 'uses' => 'Pages@login', 'middleware' => 'guest']);
Route::get('/logout', ['as' => 'logout', 'uses' => 'Pages@logout']);
