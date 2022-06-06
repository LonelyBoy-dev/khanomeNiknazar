<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;

class UserEditRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {

        if ($this->role=="user"){
            return [
                'name' => 'required|string|max:255|min:3',
                'username' => 'required|numeric|unique:users,username,'.request()->user,
                'NationalCode' => 'nullable|max:10|min:10|unique:users,NationalCode,'.request()->user,
                'mobile' => 'required|regex:/(09)[0-9]{9}/|digits:11|unique:users,mobile,'.request()->user,
                'email' => 'required|email|unique:users,email,'.request()->user,
                'password' => 'nullable|min:6|confirmed',
            ];
        }else{
            return [
                'name' => 'required|string|max:255|min:3',
                'username' => 'required|numeric|unique:users,username,'.request()->user,
                'NationalCode' => 'nullable|max:10|min:10|unique:admins,NationalCode,'.request()->user,
                'mobile' => 'required|regex:/(09)[0-9]{9}/|digits:11|unique:admins,mobile,'.request()->user,
                'email' => 'required|email|unique:users,email,'.request()->user,
                'password' => 'nullable|min:6|confirmed',
            ];
        }
    }

    public function messages()
    {
        return [
            'username.required' => 'کد کاربری را وارد کنید',
            'username.numeric' => 'کد کاربری شامل عدد می باشد',
            'username.unique' => 'کد کاربری تکراری می باشد',
            'name.required' => 'نام را وارد کنید',
            'name.min' => 'حداقل 3 کاراکتر می باشد',
            'family.required' => 'نام خانوادگی را وارد کنید',
            'family.min' => 'حداقل 3 کاراکتر می باشد',
            'national_code.unique' => 'کدملی از قبل موجود می باشد',
            'national_code.min' => 'کدملی نامعتبر است',
            'national_code.max' => 'کدملی نامعتبر است',
            'mobile.required' => 'شماره موبایل را وارد کنید',
            'mobile.digits' => 'شماره موبایل صحیح نیست',
            'mobile.unique' => 'شماره موبایل از قبل موجود می باشد',
            'mobile.regex' => 'شماره موبایل صحیح نیست',
            'email.required' => 'ایمیل  را وارد کنید',
            'email.email' => 'ایمیل صحیح نمی باشد',
            'email.unique' => 'ایمیل از قبل موجود می باشد',
            'address.min' => 'آدرس کوتاه می باشد',
            'password.required' => 'رمز عبور را وارد کنید',
            'password.min' => 'رمز عبور حداقل 6 کاراکتر می باشد',
            'password.confirmed' => 'رمز عبور و تکرار رمز عبور یکسان نیست',
            'password.regex' => 'رمز عبور باید ترکیبی از حروف لاتین و عدد باشد',
        ];
    }
}
