<?php
Route::get('/test', function () {
    
});
Route::group(['middleware' => 'auth'], function () {
    Route::get('/', ['as' => 'pages.index', 'uses' => 'Pages@index']);
    Route::get('/history', ['as' => 'pages.history', 'uses' => 'Pages@history']);
    Route::get('/score/{order?}', ['as' => 'pages.score', 'uses' => 'Pages@score']);

    Route::get('/stats/global', ['as' => 'pages.stats', 'uses' => 'Pages@statsGlobal']);
    Route::get('/stats/users', ['as' => 'pages.stats', 'uses' => 'Pages@stats']);

    Route::get('/v/{image}/{choice?}', ['as' => 'pages.vote', 'uses' => 'Pages@vote']);
    Route::get('/pending', ['as' => 'pages.pending', 'uses' => 'Pages@pending']);

    Route::get('/explog', ['as' => 'pages.explog', 'uses' => 'Pages@explog']);

    Route::post('/vote', ['as' => 'process.votes', 'uses' => 'Pages@votePost']);
});

Route::get('/login/twitter', ['as' => 'login.twitter', 'uses' => 'Pages@TwitterLogin']);
Route::get('/auth/twitter', ['as' => 'auth.twitter', 'uses' => 'Pages@TwitterAuth']);

Route::get('/login', ['as' => 'login', 'uses' => 'Pages@login', 'middleware' => 'guest']);
Route::get('/logout', ['as' => 'logout', 'uses' => 'Pages@logout']);