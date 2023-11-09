<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateJobSkillRequest extends FormRequest
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
            'job_id' => [
                'required',
                'integer',
            ],
            'skill' => [
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
            'job_id.required' => 'Bắt buộc phải nhập job id',
            'job_id.integer' => 'Job id phải là số',
            'skill.required' => 'Bắt buộc phải nhập kĩ năng cho job',
            'skill.string' => 'Kĩ năng phải là chuỗi ký tự',
            'skill.regex' => 'Kĩ năng không được chứa ký tự đặc biệt',
        ];
    }
}
