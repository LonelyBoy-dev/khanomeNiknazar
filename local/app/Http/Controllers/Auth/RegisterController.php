<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use App\Models\User;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {

        return Validator::make($data, [
            'name' => 'required|string|max:255',
            'email' => 'nullable|string|email|max:255|unique:users',
            'password' => 'required|confirmed|string|min:6',
            'mobile' => 'required|digits:11|regex:/(09)[0-9]{9}/','unique:users',
        ],[
            'name.required'=>'وارد کردن نام و نام خانوادگی الزامی است',
            'mobile.required'=>'وارد کردن ایمیل الزامی است',
            'mobile.regex'=>'شماره موبایل نامعتبر است',
            'mobile.digits'=>'شماره موبایل نامعتبر است',
            'mobile.unique'=>'شماره موبایل از قبل وجود دارد',
            'email.required'=>'وارد کردن شماره موبایل است',
            'email.email'=>'ایمیل نامعتبر است',
            'email.unique'=>'ایمیل از قبل وجود دارد',
            'password.required'=>'وارد کردن رمز ورود الزامی است',
            'password.confirmed'=>'رمز ورود و تکرار رمز ورود یکسان نیستند.'
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\Models\User
     */
    protected function create(array $data)
    {
        return User::create([
            'name' => $data['name'],
            'mobile' => $data['mobile'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
        ]);
    }
}
