<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;

class UserStoreRequest extends FormRequest
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
    protected function prepareForValidation()
    {
    }
    public function rules()
    {
            return [
                'name' => 'required|string|max:255|min:3',
                'NationalCode' => 'nullable|max:10|min:10|unique:users',
                'mobile' => 'required|regex:/(09)[0-9]{9}/|digits:11|unique:users',
                'email' => 'required|email|unique:users,email',
                'address' => 'nullable|min:10',
                'password' => 'required|min:6|confirmed',
            ];


    }
    public function messages()
    {
        return [
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
