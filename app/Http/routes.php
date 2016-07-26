<?php
use Cropan\Picture;
use Illuminate\Database\QueryException;
use Jenssegers\ImageHash\ImageHash;

Route::get('/test', function () {
    $hasher = new ImageHash;

    $pictures = Picture::whereNull('hash')->get();

    $pictures->each(function (Picture $picture) use ($hasher) {
        try {
            $picture->hash = $hasher->hash($picture->url);
            $picture->save();
        } catch (Exception $e) {
            var_dump($picture->id);
        }
    });
});
Route::group(['middleware' => 'auth'], function () {
    Route::get('/', ['as' => 'pages.index', 'uses' => 'Pages@index']);
    Route::get('/history', ['as' => 'pages.history', 'uses' => 'Pages@history']);
    Route::get('/score/{order?}', ['as' => 'pages.score', 'uses' => 'Pages@score']);

    Route::get('/stats/global', ['as' => 'pages.stats.global', 'uses' => 'StatsCtrl@global']);
    Route::get('/stats/global/{year}', ['as' => 'pages.stats.global.year', 'uses' => 'StatsCtrl@yearly']);
    Route::get('/stats/users', ['as' => 'pages.stats.users', 'uses' => 'StatsCtrl@statsUsers']);

    Route::get('/v/{image}/{choice?}', ['as' => 'pages.vote', 'uses' => 'Pages@vote']);
    Route::get('/pending', ['as' => 'pages.pending', 'uses' => 'Pages@pending']);

    Route::get('/explog', ['as' => 'pages.explog', 'uses' => 'Pages@explog']);

    Route::post('/vote', ['as' => 'process.votes', 'uses' => 'Pages@votePost']);
});

Route::get('/login/twitter', ['as' => 'login.twitter', 'uses' => 'Pages@TwitterLogin']);
Route::get('/auth/twitter', ['as' => 'auth.twitter', 'uses' => 'Pages@TwitterAuth']);

Route::get('/login', ['as' => 'login', 'uses' => 'Pages@login', 'middleware' => 'guest']);
Route::get('/logout', ['as' => 'logout', 'uses' => 'Pages@logout']);