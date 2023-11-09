<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateCVRequest extends FormRequest
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
            'cv_name' => [
                'string',
                'max:255',
                'regex:/^[^@#$&*]+$/',
            ],
            'cv_path' => [
                'file',
                'mimes:pdf',
                'max:6000',
            ],
            'cv_note' => [
                'string',
                'max:2000',
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
            'cv_name.string' => 'Tên CV phải là chuỗi ký tự',
            'cv_name.max' => 'Tên CV không được vượt quá 255 ký tự',
            'cv_name.regex' => 'Tên CV không được chứa ký tự đặc biệt (@, #, $, &, *)',

            'cv_path.file' => 'CV phải là file',
            'cv_path.mimes' => 'CV phải có định dạng pdf',
            'cv_path.max' => 'CV không được vượt quá 6MB',

            'cv_note.string' => 'Ghi chú phải là chuỗi ký tự',
            'cv_note.max' => 'Ghi chú không được vượt quá 2000 ký tự',
        ];
    }
}
