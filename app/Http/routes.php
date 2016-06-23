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

Route::get('/', function () {
    $items = \Illuminate\Database\Eloquent\Collection::make(Telegram::getUpdates());

    $items->each(function (\Telegram\Bot\Objects\Update $update) {
        $item = new \Cropan\Update();
        $item->import($update);
    });

//    var_dump(isAllowedUser(15629533));
});
