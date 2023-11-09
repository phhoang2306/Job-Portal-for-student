<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateUserExperienceRequest extends FormRequest
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
            'title' => [
                'required',
                'string',
                'regex:/^.+$/'
            ],
            'position' => [
                'required',
                'string',
                'regex:/^.+$/'
            ],
            'description' => [
                'required',
                'string',
                'regex:/^.+$/',
            ],
            'start' => [
                'required',
                'date',
            ],
            'end' => [
                'required',
                'date',
                'after:start',
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
            'title.required' => 'Bắt buộc nhập tiêu đề',
            'title.string' => 'Tiêu đề phải là chuỗi ký tự',
            // 'title.regex' => 'Tiêu đề không được chứa ký tự đặc biệt (@, #, $, &, *)',
            'position.required' => 'Bắt buộc nhập vị trí',
            'position.string' => 'Vị trí phải là chuỗi ký tự',
            'position.regex' => 'Vị trí không được chứa ký tự đặc biệt (@, #, $, &, *)',
            'description.required' => 'Bắt buộc nhập kinh nghiệm',
            'description.string' => 'Kinh nghiệm phải là chuỗi ký tự',
            // 'description.regex' => 'Kinh nghiệm không được chứa ký tự đặc biệt (@, #, $, &, *)',
            'start.required' => 'Bắt buộc nhập ngày bắt đầu',
            'start.date' => 'Ngày bắt đầu phải là dạng ngày tháng',
            'end.required' => 'Bắt buộc nhập ngày kết thúc',
            'end.date' => 'Ngày kết thúc phải là dạng ngày tháng',
            'end.after' => 'Ngày kết thúc phải sau ngày bắt đầu',
        ];
    }
}
