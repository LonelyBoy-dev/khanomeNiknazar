<?php

namespace App\Http\Requests\HairStylist;

use Illuminate\Foundation\Http\FormRequest;

class HairStylistStoreRequest extends FormRequest
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
            'NationalCode' => 'required|digits:10|unique:users',
            'email' => 'nullable|email|string|unique:users',
            'nameShop' => 'required|max:255|min:3|string',
            'mobile' => 'required|regex:/(09)[0-9]{9}/|digits:11|unique:users',
            'tell' => 'nullable|digits:11',
            'password' => 'required|min:6|confirmed',
            'Type_hairdresser' => 'required',
            'shabaNumber' => 'required|numeric',
            'accountNumber' => 'required|numeric',
        ];


    }
    public function messages()
    {
        return [
            'name.required' => 'نام و نام خانوادگی را وارد کنید',
            'name.min' => 'حدقل 3 کاراکتر',
            'NationalCode.required' => 'کد ملی را وارد کنید',
            'NationalCode.unique' => 'کد ملی متعلق به شخص دیگری است',
            'NationalCode.digits' => 'کد ملی صحیح نمی باشد',
            'mobile.required' => 'شماره موبایل را وارد کنید',
            'mobile.regex' => 'شماره موبایل نامعتبر است',
            'mobile.digits' => 'شماره موبایل نامعتبر است',
            'mobile.unique' => 'شماره موبایل از قبل موجود است',
            'national_code.min' => 'کد ملی نامعتبر است',
            'national_code.max' => 'کد ملی نامعتبر است',

            'Businesslicense.required' => 'تصویر پروانه کسب خود را آپلود کنید',
            'Businesslicense.image' => 'تصویر شما باید فرمت jpg,png باشد',
            'Businesslicense.max' => 'حجم تصویر بیشتر از 2 مگ می باشد',
            'ShopPhotos.required' => 'تصویر نمایی از مغازه خود را آپلود کنید',
            'ShopPhotos.image' => 'تصویر شما باید فرمت jpg,png باشد',
            'ShopPhotos.max' => 'حجم تصویر بیشتر از 2 مگ می باشد',
            'location.required' => 'موقعیت مکانی خود را انتخاب کنید',
            'address.required' => 'آدرس را وارد کنید',
            'address.min' => 'حدقل 10 کاراکتر',
            'nameShop.required' => 'نام مغازه را وارد کنید',
            'nameShop.min' => 'حدقل 3 کاراکتر',
            'tell.digits' => 'شماره ثابت 11 رقم می باشد',
            'accountNumber.required' => 'شماره حساب را وارد کنید',
            'accountNumber.numeric' => 'فقط از اعداد استفاده کنید',
            'shabaNumber.required' => 'شماره شباء را وارد کنید',
            'shabaNumber.numeric' => 'فقط از اعداد استفاده کنید',
            'email.required' => 'ایمیل را وارد کنید',
            'email.email' => 'ایمیل نامعتبر است',
            'email.unique' => 'ایمیل از قبل موجود است',

            'ostan.required' => 'استان خود را انتخاب کنید',
            'city.required' => 'شهر خود را انتخاب کنید',

            'Type_hairdresser.required' => 'نوع آرایشگاه را انتخاب کنید',


            'password.required' => 'پسورد را وارد کنید',
            'password.min' => 'حداقل پسورد 6 کاراکتر است',
            'password.confirmed' => ' رمز ورود و تکرار رمز ورود یکسان نیست',
        ];
    }
}
