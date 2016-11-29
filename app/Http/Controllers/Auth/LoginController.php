<?php

namespace Cropan\Http\Controllers\Auth;

use Cropan\Http\Controllers\Controller;
use Cropan\User;
use Exception;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use League\OAuth1\Client\Credentials\CredentialsException;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest', ['except' => 'logout']);
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
            return \Redirect::route('pages.front');
        } catch (Exception $e) {
            return \Redirect::route('pages.front');
        }
    }
}
