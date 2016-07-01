<?php

namespace Cropan\Http\Controllers;

use Cropan\Http\Requests;
use Cropan\Picture;
use Cropan\Stats;
use Cropan\User;
use Cropan\Vote;
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
        $stats = new Stats();
        
        $positiveRanking = $stats->positiveRanking();
        $negativeRanking = $stats->negativeRanking();

        $ratioTumblr = $stats->tumblrRanking();
        $ratioYLD = $stats->yesRatio();
        $ratioNO = $stats->noRatio();

        $uncommonTaste = $stats->uncommonTaste();
        $nitpicker = $stats->nitPicker();

        return view('pages.stats')
            ->with('positiveRanking', $positiveRanking)
            ->with('negativeRanking', $negativeRanking)
            ->with('ratioTumblr', $ratioTumblr)
            ->with('ratioYLD', $ratioYLD)
            ->with('ratioNO', $ratioNO)
            ->with('uncommonTaste', $uncommonTaste)
            ->with('nitpicker', $nitpicker);
    }

    public function vote(Picture $image, $choice)
    {
        switch (strtoupper($choice)) {
            case 'YLD' :
                $choice = true;
                break;

            case 'NO' :
                $choice = false;
                break;
        }

        $vote = Vote::firstOrCreate([
            'picture_id' => $image->id,
            'user_id' => \Auth::user()->telegram_id
        ]);

        $vote->vote = $choice;
        $vote->save();

        return view('pages.vote')->with('picture', $image)->with('vote', $vote);
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

            return \Redirect::intended();
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
