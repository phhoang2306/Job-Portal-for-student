<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdatePasswordRequest extends FormRequest
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
            'current_password' => [
                'required',
            ],
            'new_password' => [
                'required',
                'min:8',
                // regex: at least 1 letter and 1 number and no special characters
                'regex:/^(?=.*[A-Za-z])(?=.*\d)[A-Za-z\d]{8,20}$/',
            ],
            'confirm_password' => [
                'required',
                'same:new_password',
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
            'current_password.required' => 'Mật khẩu hiện tại không được để trống',
            'new_password.required' => 'Mật khẩu mới không được để trống',
            'new_password.min' => 'Mật khẩu phải có ít nhất 8 ký tự',
            'new_password.regex' => 'Mật khẩu phải có ít nhất 1 chữ cái và 1 số',
            'confirm_password.required' => 'Nhập lại mật khẩu không được để trống',
            'confirm_password.same' => 'Nhập lại mật khẩu không khớp',
        ];
    }
}
