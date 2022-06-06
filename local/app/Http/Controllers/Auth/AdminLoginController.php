<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\URL;

class AdminLoginController extends Controller
{
    public function __construct()
    {
        //$this->middleware('guest:admin',['except'=>['logout']]);
    }

    public function showLoginForm()
    {
        if (Auth::guard('admin')->user()){
            return redirect('/admin/dashboard');
        }else{
            session()->put('AdminUrlBack', url()->previous());
            return view('auth.admin-login');
        }

    }

    public function login(Request $request)
    {
        $this->validate($request, [
            'email'   => 'required|email',
            'password' => 'required|min:6'
        ],[
            'email.required'=>'فیلد ایمیل الزامی می باشد.',
            'email.email'=>'فرمت ایمیل صحیح نمی باشد.',
            'password.required'=>'فیلد رمز ورود الزامی می باشد.',
            'password.min'=>'فیلد رمز ورود 6 کاراکتر الزامی می باشد.',
        ]);

        // Attempt to log the user in
        if (Auth::guard('admin')->attempt(['email' => $request->email, 'password' => $request->password], $request->remember)) {
            // if successful, then redirect to their intended location
            if (admin()->status=="ACTIVE"){
                Admin::where('id', admin()->id)->update(['updated_at' => Carbon::now()->format('Y-m-d H:m:s')]);

                    return redirect('/admin/dashboard');

               // return redirect('/admin/dashboard');
            }else{
                Auth::guard('admin')->logout();
                session()->put('admin_login_error','حساب کاربری شما غیر فعال می باشد.');
                return redirect('/admin/login');
            }

        }

        session()->put('admin_login_error','ایمیل یا رمز ورود صحیح نمی باشد.');
        // if unsuccessful, then redirect back to the login with the form data
        return redirect()->back()->withInput($request->only('email', 'remember'));
    }
    public function logout()
    {
        Auth::guard('admin')->logout();
        return redirect('/admin/login');
    }
}
