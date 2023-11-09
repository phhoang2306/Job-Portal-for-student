<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateJobRequest extends FormRequest
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
                'string',
                'min:10',
                // regex: not allow special characters (@, #, $, &, *)
                'regex:/^[^@#$&*]+$/',
            ],
            'description' => [
                'string',
                'min:10',
                // regex: not allow special characters (@, #, $, &, *)
                'regex:/^[^@#$&*]+$/',
            ],
            'benefit' => [
                'string',
                'min:10',
                // regex: not allow special characters (@, #, $, &, *)
                'regex:/^[^@#$&*]+$/',
            ],
            'requirement' => [
                'string',
                // regex: not allow special characters (@, #, $, &, *)
                'regex:/^[^@#$&*]+$/',
            ],
            'type' => [
                'string',
                // regex: not allow special characters (@, #, $, &, *)
                'regex:/^[^@#$&*]+$/',
            ],
            'location' => [
                'string',
                // regex: not allow special characters (@, #, $, &, *)
                'regex:/^[^@#$&*]+$/',
            ],
            'min_salary' => [
                'numeric',
            ],
            'max_salary' => [
                'numeric',
            ],
            'recruit_num' => [
                'numeric',
            ],
            'deadline' => [
                'date',
            ],
            'position' => [
                'string',
                // regex: not allow special characters (@, #, $, &, *)
                'regex:/^[^@#$&*]+$/',
            ],
            'min_yoe' => [
                'numeric',
            ],
            'max_yoe' => [
                'numeric',
            ],
            'gender' => [
                'string',
                // regex: not allow special characters (@, #, $, &, *)
                'regex:/^[^@#$&*]+$/',
            ],
            'status' => [
                'string',
                'regex:/^[^@#$&*]+$/',
            ]
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
            'title.string' => 'Tiêu đề phải là chuỗi ký tự',
            'title.min' => 'Tiêu đề phải có ít nhất 10 ký tự',
            'title.regex' => 'Tiêu đề không được chứa ký tự đặc biệt (@, #, $, &, *)',

            'description.string' => 'Mô tả phải là chuỗi ký tự',
            'description.min' => 'Mô tả phải có ít nhất 10 ký tự',
            'description.regex' => 'Mô tả không được chứa ký tự đặc biệt (@, #, $, &, *)',

            'benefit.string' => 'Quyền lợi phải là chuỗi ký tự',
            'benefit.min' => 'Quyền lợi phải có ít nhất 10 ký tự',
            'benefit.regex' => 'Quyền lợi không được chứa ký tự đặc biệt (@, #, $, &, *)',

            'requirement.string' => 'Yêu cầu phải là chuỗi ký tự',
            'requirement.regex' => 'Yêu cầu không được chứa ký tự đặc biệt (@, #, $, &, *)',

            'type.string' => 'Loại công việc phải là chuỗi ký tự',
            'type.regex' => 'Loại công việc không được chứa ký tự đặc biệt (@, #, $, &, *)',

            'location.string' => 'Địa điểm phải là chuỗi ký tự',
            'location.regex' => 'Địa điểm không được chứa ký tự đặc biệt (@, #, $, &, *)',

            'min_salary.numeric' => 'Mức lương tối thiểu phải là số',

            'max_salary.numeric' => 'Mức lương tối đa phải là số',

            'recruit_num.numeric' => 'Số lượng tuyển dụng phải là số',

            'deadline.date' => 'Hạn nộp hồ sơ phải là ngày tháng',

            'position.string' => 'Vị trí tuyển dụng phải là chuỗi ký tự',
            'position.regex' => 'Vị trí tuyển dụng không được chứa ký tự đặc biệt (@, #, $, &, *)',

            'min_yoe.numeric' => 'Số năm kinh nghiệm tối thiểu phải là số',

            'max_yoe.numeric' => 'Số năm kinh nghiệm tối đa phải là số',

            'gender.string' => 'Giới tính phải là chuỗi ký tự',
            'gender.regex' => 'Giới tính không được chứa ký tự đặc biệt (@, #, $, &, *)',

            'status.string' => 'Trạng thái công việc phải là chuỗi ký tự',
            'status.regex' => 'Trạng thái công việc không được chứa ký tự đặc biệt (@, #, $, &, *)',
        ];
    }
}
