<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateSavedJobRequest extends FormRequest
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
            'user_id' => [
                'required',
                'string',
            ],
            'job_id' => [
                'required',
                'string',
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
            'user_id.required' => 'Bắt buộc phải nhập user id',
            'user_id.string' => 'User id phải là chuỗi ký tự',

            'job_id.required' => 'Bắt buộc phải nhập job id',
            'job_id.string' => 'Job id phải là chuỗi ký tự',
        ];
    }
}
