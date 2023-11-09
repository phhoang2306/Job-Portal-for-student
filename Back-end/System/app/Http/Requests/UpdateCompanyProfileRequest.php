<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateCompanyProfileRequest extends FormRequest
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
            'name' => [
                'string',
                'max:500',
                'regex:/^[a-zA-Z\s]+$/'
            ],
            'description' => [
                'string',
                'max:2000',
            ],
            'site' => [
                'string',
                'max:500',
                // regex url
                'regex:/^((http|https:\/\/)?(www.)?)?([a-zA-Z0-9]+).[a-zA-Z0-9]*.[a-z]{0,5}(\.[a-z]{2,5})?$/',
            ],
            'address' => [
                'string',
                'max:500',
                'regex:/^[^@#$&*]+$/',
            ],
            'size' => [
                'string',
                'max:255',
                'regex:/^[^@#$&*]+$/',
            ],
            'phone' => [
                'string',
                // regex for vietnamese phone number, must include 84 or +84 or 0
                'regex:/^(84|0|\\+84)(3|5|7|8|9)([0-9]{8})$/',
            ],
            'email' => [
                'string',
                'max:255',
                // regex email
                'regex:/^[a-zA-Z0-9_.+-]+@[a-zA-Z0-9-]+\.[a-zA-Z0-9-.]+$/',
            ],
            'logo' => [
                // file type
                'mimes:jpeg,jpg,png',
                // max file size
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
            'name.string' => 'Tên công ty phải là chuỗi ký tự.',
            'name.max' => 'Tên công ty không được vượt quá 500 ký tự.',
            'name.regex' => 'Tên công ty không được chứa ký tự đặc biệt.',

            'description.string' => 'Mô tả công ty phải là chuỗi ký tự.',
            'description.max' => 'Mô tả công ty không được vượt quá 2000 ký tự.',

            'site.string' => 'Trang web phải là chuỗi ký tự.',
            'site.max' => 'Trang web không được vượt quá 500 ký tự.',
            'site.regex' => 'Trang web không hợp lệ.',

            'address.string' => 'Địa chỉ phải là chuỗi ký tự.',
            'address.max' => 'Địa chỉ không được vượt quá 500 ký tự.',
            'address.regex' => 'Địa chỉ không được chứa ký tự đặc biệt (@, #, $, &, *).',

            'size.string' => 'Quy mô công ty phải là chuỗi ký tự.',
            'size.max' => 'Quy mô công ty không được vượt quá 255 ký tự.',
            'size.regex' => 'Quy mô công ty không được chứa ký tự đặc biệt (@, #, $, &, *).',

            'phone.string' => 'Số điện thoại phải là chuỗi ký tự.',
            'phone.regex' => 'Số điện thoại không hợp lệ.',

            'email.string' => 'Email phải là chuỗi ký tự.',
            'email.max' => 'Email không được vượt quá 255 ký tự.',
            'email.regex' => 'Email không hợp lệ.',

            'logo.mimes' => 'Logo phải là file ảnh có định dạng jpeg, jpg, png.',
            'logo.max' => 'Logo không được vượt quá 4MB.',
        ];
    }
}
