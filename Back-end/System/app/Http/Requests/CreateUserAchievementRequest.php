<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateUserAchievementRequest extends FormRequest
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
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'description' => [
                'required',
                'string',
                // regex: not allow special characters (@, #, $, &, *)
                'regex:/^[^@#$&*]+$/',
            ],
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array<string, mixed>
     */
    public function messages()
    {
        return [
            'description.required' => 'Bắt buộc nhập mô tả thành tích',
            'description.string' => 'Mô tả thành tích phải là chuỗi ký tự',
            'description.regex' => 'Mô tả thành tích không được chứa ký tự đặc biệt (@, #, $, &, *)',
        ];
    }
}
