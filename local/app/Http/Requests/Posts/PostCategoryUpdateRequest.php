<?php

namespace App\Http\Requests\Posts;

use Illuminate\Foundation\Http\FormRequest;

class PostCategoryUpdateRequest extends FormRequest
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
    {
            return [
                'title' => 'required|string|max:255|min:3',
                'slug' => 'required|string|max:255|min:3|unique:post_categories,slug,'.request()->route('id'),

            ];

    }
    public function messages()
    {
        return [
            'title.required' => 'عنوان دسته بندی را وارد کنید',
            'title.min' => 'عنوان حداقل 3 کاراکتر می باشد',
            'slug.required' => 'نامک را وارد کنید',
            'slug.unique' => 'نامک از قبل ثبت شده است',
            'slug.min' => 'نامک حداقل 3 کاراکتر می باشد',

        ];
    }
}
