<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateModRequest extends FormRequest
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
            'full_name' => [
                'required',
                'string',
                'min:3',
                'max:100',
                'regex:/^[a-zA-Z\s]+$/'
            ],
            'avatar' => [
                'mimes:jpeg,jpg,png',
                'max:4096',
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
            'full_name.required' => 'Bắt buộc phải nhập họ tên',
            'full_name.string' => 'Họ tên phải là chuỗi ký tự',
            'full_name.min' => 'Họ tên phải có ít nhất 3 ký tự',
            'full_name.max' => 'Họ tên phải có tối đa 100 ký tự',
            'full_name.regex' => 'Họ tên không được chứa ký tự đặc biệt',

            'avatar.mimes' => 'Avatar sai định dạng (chỉ chấp nhận jpg, jpeg, png)',
            'avatar.max' => 'Avatar quá lớn (tối đa 4MB)',
        ];
    }
}
