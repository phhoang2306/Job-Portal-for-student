<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateUserProfileRequest extends FormRequest
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
                'string',
                'max:255',
                'regex:/^[^@#$&*]+$/'
            ],
            'about_me' => [
                'string',
                'max:2000',
            ],
            'good_at_position' => [
                'string',
                'max:255',
                'regex:/^[^@#$&*]+$/',
            ],
            'year_of_experience' => [
                'string',
                'max:255',
                'regex:/^[^@#$&*]+$/',
            ],
            'date_of_birth' => [
                'date',
            ],
            'gender' => [
                'string',
                'max:50',
                'regex:/^[^@#$&*]+$/',
            ],
            'address' => [
                'string',
                'max:500',
                'regex:/^[^@#$&*]+$/',
            ],
            'email' => [
                'string',
                'max:500',
                // regex for email
                'regex:/^[a-zA-Z0-9_.+-]+@[a-zA-Z0-9-]+\.[a-zA-Z0-9-.]+$/',
            ],
            'phone' => [
                'string',
                // regex for vietnamese phone number, must include 84 or +84 or 0
                'regex: /^(84|0|\\+84)(3|5|7|8|9)([0-9]{8})$/',
            ],
            'avatar' => [
                // file type
                'mimes:jpeg,png,jpg',
                // max file size
                'max:4096',
            ],
            'is_private' => [
                'boolean',
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
            'full_name.string' => 'Họ tên phải là chuỗi ký tự.',
            'full_name.max' => 'Họ tên không được vượt quá 255 ký tự.',
            'full_name.regex' => 'Họ tên không được chứa ký tự đặc biệt (@, #, $, &, *).',

            'about_me.string' => 'Giới thiệu bản thân phải là chuỗi ký tự.',
            'about_me.max' => 'Giới thiệu bản thân không được vượt quá 2000 ký tự.',

            'good_at_position.string' => 'Vị trí mong muốn phải là chuỗi ký tự.',
            'good_at_position.max' => 'Vị trí mong muốn không được vượt quá 255 ký tự.',
            'good_at_position.regex' => 'Vị trí mong muốn không được chứa ký tự đặc biệt (@, #, $, &, *).',

            'year_of_experience.string' => 'Số năm kinh nghiệm phải là chuỗi ký tự.',
            'year_of_experience.max' => 'Số năm kinh nghiệm không được vượt quá 255 ký tự.',
            'year_of_experience.regex' => 'Số năm kinh nghiệm không được chứa ký tự đặc biệt (@, #, $, &, *).',

            'date_of_birth.date' => 'Ngày tháng năm sinh phải là dạng ngày tháng.',

            'gender.string' => 'Giới tính phải là dạng chuỗi.',
            'gender.max' => 'Giới tính không được vượt quá 10 ký tự.',
            'gender.regex' => 'Giới tính không được chứa ký tự đặc biệt (@, #, $, &, *).',

            'address.string' => 'Địa chỉ phải là chuỗi ký tự.',
            'address.max' => 'Địa chỉ không được vượt quá 500 ký tự.',
            'address.regex' => 'Địa chỉ không được chứa ký tự đặc biệt (@, #, $, &, *).',

            'email.string' => 'Email phải là chuỗi ký tự.',
            'email.max' => 'Email không được vượt quá 500 ký tự.',
            'email.regex' => 'Email không đúng định dạng.',

            'phone.string' => 'Số điện thoại phải là chuỗi ký tự.',
            'phone.regex' => 'Số điện thoại không đúng định dạng.',

            'avatar.mimes' => 'Ảnh đại diện phải là định dạng jpeg, png, jpg.',
            'avatar.max' => 'Ảnh đại diện không được vượt quá 4MB.',

            'is_private.boolean' => 'Trạng thái riêng tư phải là dạng boolean.',
        ];
    }
}
