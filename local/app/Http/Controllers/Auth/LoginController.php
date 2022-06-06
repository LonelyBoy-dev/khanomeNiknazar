<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;

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

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest',['except'=>['logout','userLogout']]);
    }
    public function userLogout()
    {
        Auth::guard('web')->logout();
        return redirect('/');
    }



    // google Login
    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }
    public function handleGoogleCallback()
    {

        $user = Socialite::driver('google')->user();
        $this->_registerOrLoginUser($user);
        return redirect('/');
    }

    public function _registerOrLoginUser($data)
    {
        $user=User::where('email',$data->email)->first();
        if (!$user){
            $user=new User();
            $user->name=$data->name;
            $user->email=$data->email;
            $user->provider_id=$data->id;
            $user->avatar=$data->avatar;
            $user->save();
        }
        Auth::login($user);
    }
}
