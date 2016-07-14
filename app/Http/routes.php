<?php

//Route::group(['middleware' => 'auth'], function () {
//    Route::get('/', ['as' => 'pages.index', 'uses' => 'Pages@index']);
//    Route::get('/history', ['as' => 'pages.history', 'uses' => 'Pages@history']);
//    Route::get('/stats', ['as' => 'pages.stats', 'uses' => 'Pages@stats']);
//
//    Route::pattern('vote', 'yld|no');
//    Route::get('/v/{image}/{choice?}', ['as' => 'pages.vote', 'uses' => 'Pages@vote']);
//    Route::get('/pending', ['as' => 'pages.pending', 'uses' => 'Pages@pending']);
//
//    Route::post('/vote', ['as' => 'process.votes', 'uses' => 'Pages@votePost']);
//});
//
//Route::get('/login/twitter', ['as' => 'login.twitter', 'uses' => 'Pages@TwitterLogin']);
//Route::get('/auth/twitter', ['as' => 'auth.twitter', 'uses' => 'Pages@TwitterAuth']);
//
//Route::get('/login', ['as' => 'login', 'uses' => 'Pages@login', 'middleware' => 'guest']);
//Route::get('/logout', ['as' => 'logout', 'uses' => 'Pages@logout']);

use Cropan\User;

Route::get('/', function () {
    $u = User::where('id', 4)->first();

    var_dump($u->toArray());
});