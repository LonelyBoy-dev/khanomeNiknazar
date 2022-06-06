<?php

namespace App\Http\Requests\Posts;

use Illuminate\Foundation\Http\FormRequest;

class PostUpdateRequest extends FormRequest
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
        if($this->input('slug')){
            $this->merge(['slug' => make_slug($this->input('slug'))]);
        }else{
            $this->merge(['slug' => make_slug($this->input('title'))]);
        }
    }
    public function rules()
    {/*
        return [
            'title' => 'required|string|max:255|min:3',
            'slug' => 'required|string|max:255|min:3|unique:posts,slug,'.request()->route('post'),
            'shortContent'=>'required|string|min:20',
            'seoTitle'=>'required|string|min:6',
            'seoContent'=>'required|string|min:10',
            'category'=>'required',
            'feature_image'=>'required',
        ];*/
        if ($this->input('target')) {
            if ($this->input('target') == 1) {
                return [
                    'title' => 'required|string|max:255|min:3',
                    'shortContent'=>'required|string|min:20',
                    'category'=>'required',
                    'exam'=>'required',
                    'score'=>'required',
                    'module'=>'required',
                    'feature_image'=>'required',
                ];
            } elseif ($this->input('target') == 2) {
                return [
                    'title' => 'required|string|max:255|min:3',
                    'shortContent'=>'required|string|min:20',
                    'category'=>'required',
                    'notexam'=>'required',
                    'feature_image'=>'required',
                ];
            }
        }else{
            return [
                'title' => 'required|string|max:255|min:3',
                'shortContent'=>'required|string|min:20',
                'category'=>'required',
                'target'=>'required',
                'feature_image'=>'required',
            ];
        }

    }
    public function messages()
    {
        return [
            'title.required' => 'عنوان دسته بندی را وارد کنید',
            'title.min' => 'عنوان حداقل 3 کاراکتر می باشد',
            'slug.required' => 'نامک را وارد کنید',
            'slug.unique' => 'نامک از قبل ثبت شده است',
            'slug.min' => 'نامک حداقل 3 کاراکتر می باشد',
            'shortContent.required' => 'خلاصه مطلب را وارد کنید',
            'shortContent.min' => 'خلاصه مطلب حداقل 20 کاراکتر می باشد',
            'seoTitle.required' => 'عنوان سئو را وارد کنید',
            'seoTitle.min' => 'عنوان سئو حداقل 6 کاراکتر می باشد',
            'seoContent.required' => 'توضیحات سئو را وارد کنید',
            'seoContent.min' => 'توضیحات سئو حداقل 10 کاراکتر می باشد',
            'target.required' => 'هدف را انتخاب کنید',
            'category.required' => 'دسته بندی مطلب را انتخاب کنید',
            'exam.required' => 'آزمون ها را انتخاب کنید',
            'score.required' => 'نمره مورد نیاز را انتخاب کنید',
            'module.required' => 'ماژول امتحان را انتخاب کنید',
            'notexam.required' => 'هدف های غیر آزمون را انتخاب کنید',
            'feature_image.required' => 'تصویر شاخص مطلب را انتخاب کنید',
        ];
    }
}
