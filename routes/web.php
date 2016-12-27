<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| This file is where you may define all of the routes that are handled
| by your application. Just tell Laravel the URIs it should respond
| to using a Closure or controller method. Build something great!
|
*/

/** @var Router $router */
use Cropan\Diary;
use Illuminate\Routing\Router;

$router->pattern('order', 'asc|desc');
$router->pattern('vote', 'yld|no');
$router->pattern('criteria', 'alphabet|rating');

$router->get('/', ['as' => 'pages.front', 'uses' => 'PagesCtrl@index'])->middleware('guest');

$router->get('/login', ['as' => 'login.twitter', 'uses' => 'Auth\LoginController@TwitterLogin']);
$router->get('/login/twitter', ['as' => 'login.twitter', 'uses' => 'Auth\LoginController@TwitterLogin']);
$router->get('/auth/twitter', ['as' => 'auth.twitter', 'uses' => 'Auth\LoginController@TwitterAuth']);
$router->post('/logout', ['as' => 'logout', 'uses' => 'Auth\LoginController@logout']);

$router->group(['middleware' => 'auth'], function (Router $router) {
    $router->get('/home', ['as' => 'pages.index', 'uses' => 'PagesCtrl@home']);
    $router->get('/history', ['as' => 'pages.history', 'uses' => 'PagesCtrl@history']);
    $router->get('/ranking/{order?}', ['as' => 'pages.ranking', 'uses' => 'PagesCtrl@ranking']);
    $router->get('/directory/{criteria?}', ['as' => 'pages.directory', 'uses' => 'PagesCtrl@directory']);
    $router->get('/directory/{slug}', ['as' => 'pages.directory.person', 'uses' => 'PagesCtrl@person']);
    $router->get('/sent', ['as' => 'pages.sent', 'uses' => 'PagesCtrl@sent']);
    $router->get('/pending', ['as' => 'pages.pending', 'uses' => 'PagesCtrl@pending']);
    $router->get('/cropanon', ['as' => 'pages.unnamed', 'uses' => 'PagesCtrl@unnamed']);
    $router->get('/v/{picture}/{choice?}', ['as' => 'pages.picture', 'uses' => 'PagesCtrl@picture']);

    $router->get('/stats/global', ['as' => 'pages.stats.global', 'uses' => 'StatsCtrl@global']);
    $router->get('/stats/global/{year}', ['as' => 'pages.stats.global.year', 'uses' => 'StatsCtrl@yearly']);
    $router->get('/stats/users', ['as' => 'pages.stats.users', 'uses' => 'StatsCtrl@statsUsers']);

    $router->post('/vote', ['as' => 'pages.vote', 'uses' => 'PagesCtrl@vote']);
    $router->post('/tag', ['as' => 'pages.tag', 'uses' => 'PagesCtrl@tag']);
    $router->post('/untag', ['as' => 'pages.untag', 'uses' => 'PagesCtrl@untag']);
});

$router->get('/test', function () {
    Telegram::sendAudio([
        'chat_id' => env('TELEGRAM_GROUP_ID'),
        'audio' => storage_path('app/public/01.mp3'),
        'performer' => 'CropanBot',
        'title' => ''
    ]);
});