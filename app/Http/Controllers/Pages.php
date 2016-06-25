<?php

namespace Cropan\Http\Controllers;

use Cropan\Http\Requests;
use Cropan\Picture;
use Cropan\User;
use League\OAuth1\Client\Credentials\CredentialsException;

class Pages extends Controller
{
    public function index()
    {
        $pictures = Picture::published()->orderBy('published_at', 'desc')->orderBy('id', 'desc')->paginate(9);

        return view('pages.index')->with('pictures', $pictures);
    }

    public function history()
    {
        $pictures = Picture::sent()->orderBy('sent_at', 'desc')->paginate(15);

        return view('pages.history')->with('pictures', $pictures);
    }

    public function TwitterLogin()
    {
        return \Socialite::driver('twitter')->redirect();
    }

    public function TwitterAuth()
    {
        try {
            $user = \Socialite::driver('twitter')->user();
            $local_user = User::where('nickname', $user->nickname)->first();

            if (!is_null($local_user)) {
                if (is_null($local_user->avatar)) {
                    $local_user->avatar = $user->getAvatar();
                    $local_user->save();
                }

                \Auth::login($local_user);
            }

            return \Redirect::route('pages.index');
        } catch (CredentialsException $e) {
            return \Redirect::route('pages.index');
        }
    }

    public function login()
    {
        return view('pages.login');
    }

    public function logout()
    {
        \Auth::logout();

        return \Redirect::route('pages.index');
    }
}
