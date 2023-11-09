<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateApplicationRequest extends FormRequest
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
                // regex: not allow special characters
                'regex:/^[a-zA-Z0-9\s]+$/',
            ],
            'cv_id' => [
                'required',
                'string',
            ],
        ];
    }

    /**
     * Get the validation messages that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function messages()
    {
        return [
            'job_id.required' => 'Yêu cầu nhập job_id',
            'job_id.regex' => 'job_id không được chứa ký tự đặc biệt',

            'cv_id.required' => 'Yêu cầu nhập CV',
            'cv_id.string' => 'CV phải là chuỗi',
        ];
    }
}
