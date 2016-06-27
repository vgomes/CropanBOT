<?php

namespace Cropan\Http\Controllers;

use Cropan\Http\Requests;
use Cropan\Picture;
use Cropan\User;
use Cropan\Vote;
use Khill\Lavacharts\Lavacharts;
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

    public function stats()
    {
        $positiveRanking = Picture::has('votes')->sent()->orderBy('score', 'desc')->orderBy('yes', 'desc')->orderBy('no', 'asc')->take(6)->get();
        $negativeRanking = Picture::has('votes')->sent()->orderBy('score', 'asc')->orderBy('no', 'desc')->orderBy('yes', 'asc')->take(6)->get();

        $users = User::has('votes')->get();

        $users->each(function (User $user) {
            $yes = 0;
            $no = 0;

            $user->votes()->each(function (Vote $vote) use (&$yes, &$no) {
                if ($vote->vote) {
                    $yes += 1;
                } else {
                    $no += 1;
                }
            });

            $user->yes = $yes;
            $user->no = $no;
            $user->yesPercent = $yes / ($yes + $no);
            $user->noPercent = $no / ($yes + $no);

            $user->sent = $user->pictures()->count();
            $user->published = Picture::where('user_id', $user->telegram_id)->published()->count();

            if ($user->sent > 0) {
                $user->publishedPercent = $user->published / $user->sent;
            } else {
                $user->publishedPercent = 0;
            }
        });

        $ratioTumblr = $users->sortByDesc('publishedPercent');
        $ratioYLD = $users->sortByDesc('yesPercent');
        $ratioNO = $users->sortByDesc('noPercent');

        return view('pages.stats')
            ->with('positiveRanking', $positiveRanking)
            ->with('negativeRanking', $negativeRanking)
            ->with('ratioTumblr', $ratioTumblr)
            ->with('ratioYLD', $ratioYLD)
            ->with('ratioNO', $ratioNO);
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
