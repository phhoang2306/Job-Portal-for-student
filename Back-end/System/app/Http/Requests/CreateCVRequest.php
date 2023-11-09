<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateCVRequest extends FormRequest
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
                'required',
                'string',
                'max:255',
                'regex:/^[^@#$&*]+$/',
            ],
            'cv_path' => [
                'required',
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
            'cv_name.required' => 'Bắt buộc phải có tên CV',
            'cv_name.string' => 'Tên CV phải là chuỗi ký tự',
            'cv_name.max' => 'Tên CV không được vượt quá 255 ký tự',
            'cv_name.regex' => 'Tên CV không được chứa ký tự đặc biệt (@, #, $, &, *)',

            'cv_path.required' => 'Bắt buộc phải có file CV',
            'cv_path.file' => 'File CV phải là dạng file',
            'cv_path.mimes' => 'File CV phải là dạng PDF',
            'cv_path.max' => 'File CV không được vượt quá 6MB',

            'cv_note.string' => 'CV note phải là chuỗi ký tự',
            'cv_note.max' => 'CV note không được vượt quá 2000 ký tự',
        ];
    }
}
