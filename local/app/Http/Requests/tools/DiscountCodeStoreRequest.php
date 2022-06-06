<?php

namespace App\Http\Requests\tools;

use Illuminate\Foundation\Http\FormRequest;

class DiscountCodeStoreRequest extends FormRequest
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
                'code' => 'required|unique:discount_codes',
                'darsad' => 'required',
                'max' => 'required',
                'end_date' => 'required',
            ];


    }
    public function messages()
    {
        return [
            'code.required'=>'فیلد کد نمی تواند خالی باشد',
            'code.unique'=>'فیلد کد نمی تواند تکراری باشد',
            'darsad.required'=>'فیلد درصد تخفیف نمی تواند خالی باشد',
            'max.required'=>'فیلد تعداد قابل استفاده نمی تواند خالی باشد',
            'end_date.required'=>'فیلد ناریخ اتمام نمی تواند خالی باشد',
        ];
    }
}
