<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CompanySignUpRequest extends FormRequest
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
            'username' => [
                'required',
                'string',
                // regex: not allow special characters
                'regex:/^[a-zA-Z0-9\s]+$/',
                'min:4',
                'max:20',
            ],
            'password' => [
                'required',
                'string',
                'max:20',
                'min:8',
                // regex: at least 1 letter and 1 number and no special characters
                'regex:/^(?=.*[A-Za-z])(?=.*\d)[A-Za-z\d]{8,20}$/',
            ],
            'confirm_password' => [
                'required',
                'string',
                'same:password',
            ],
            'name' => [
                'required',
                'string',
                'max:100',
                'min:4',
            ],
            'description' => [
                'required',
                'string',
                'max:10000',
            ],
            'site' => [
                'nullable',
                'string',
                'max:500',
            ],
            'address' => [
                'required',
                'string',
                'max:1000',
            ],
            'size' => [
                'required',
                'string',
                'max:100',
            ],
            'phone' => [
                'required',
                'string',
                // regex for vietnamese phone number, must include 84 or +84 or 0
                'regex:/^(84|0[3|5|7|8|9])+([0-9]{8})$/',
            ],
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
            ],
        ];
    }

    /**
     * Get the validation messages that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function messages(): array
    {
        return [
            'username.required' => 'Yêu cầu nhập tên đăng nhập',
            'username.string' => 'Tên đăng nhập phải là dạng chuỗi',
            'username.regex' => 'Tên đăng nhập không được chứa ký tự đặc biệt',
            'username.max' => 'Tên đăng nhập không được quá 20 ký tự',
            'username.min' => 'Tên đăng nhập phải có ít nhất 4 ký tự',

            'password.required' => 'Yêu cầu nhập mật khẩu',
            'password.string' => 'Mật khẩu phải là dạng chuỗi',
            'password.max' => 'Mật khẩu không được quá 20 ký tự',
            'password.min' => 'Mật khẩu phải có ít nhất 8 ký tự',
            'password.regex' => 'Mật khẩu phải có ít nhất 1 chữ cái, 1 chữ số và không có ký tự đặc biệt',

            'confirm_password.required' => 'Yêu cầu nhập lại mật khẩu xác nhận',
            'confirm_password.string' => 'Mật khẩu xác nhận phải là dạng chuỗi',
            'confirm_password.same' => 'Xác nhận mật khẩu không khớp',

            'name.required' => 'Yêu cầu nhập tên công ty',
            'name.string' => 'Tên công ty phải là dạng chuỗi',
            'name.max' => 'Tên công ty không được quá 100 ký tự',
            'name.min' => 'Tên công ty phải có ít nhất 4 ký tự',

            'description.required' => 'Yêu cầu nhập mô tả công ty',
            'description.string' => 'Mô tả công ty phải là dạng chuỗi',
            'description.max' => 'Mô tả công ty không được quá 10000 ký tự',

            'site.string' => 'Trang web công ty phải là dạng chuỗi',
            'site.max' => 'Trang web công ty không được quá 500 ký tự',

            'address.required' => 'Yêu cầu nhập địa chỉ công ty',
            'address.string' => 'Địa chỉ công ty phải là dạng chuỗi',
            'address.max' => 'Địa chỉ công ty không được quá 1000 ký tự',

            'size.required' => 'Yêu cầu nhập quy mô công ty',
            'size.string' => 'Quy mô công ty phải là dạng chuỗi',
            'size.max' => 'Quy mô công ty không được quá 100 ký tự',

            'phone.required' => 'Yêu cầu nhập số điện thoại',
            'phone.string' => 'Số điện thoại phải là dạng chuỗi',
            'phone.regex' => 'Số điện thoại không hợp lệ',

            'email.required' => 'Yêu cầu nhập email',
            'email.string' => 'Email phải là dạng chuỗi',
            'email.email' => 'Email không hợp lệ',
            'email.max' => 'Email không được quá 255 ký tự',
        ];
    }
}
