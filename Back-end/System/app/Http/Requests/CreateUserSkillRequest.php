<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateUserSkillRequest extends FormRequest
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
            'skill' => [
                'required',
                'string',
                // regex: not allow special characters (@, $, &, *)
                'regex:/^[^@$&*]+$/',
            ]
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
            'skill.required' => 'Bắt buộc nhập kỹ năng',
            'skill.string' => 'Kỹ năng phải là chuỗi ký tự',
            'skill.regex' => 'Kỹ năng không được chứa ký tự đặc biệt (@, $, &, *)',
        ];
    }
}
