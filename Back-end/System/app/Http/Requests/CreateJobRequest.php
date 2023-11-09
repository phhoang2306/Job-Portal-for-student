<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateJobRequest extends FormRequest
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
                'min:10',
                // regex: not allow special characters (@, #, $, &, *)
                'regex:/^[^@#$&*]+$/',
            ],
            'description' => [
                'required',
                'string',
                'min:10',
                // regex: not allow special characters (@, #, $, &, *)
                'regex:/^[^@#$&*]+$/',
            ],
            'benefit' => [
                'required',
                'string',
                'min:10',
                // regex: not allow special characters (@, #, $, &, *)
                'regex:/^[^@#$&*]+$/',
            ],
            'requirement' => [
                'required',
                'string',
                // regex: not allow special characters (@, #, $, &, *)
                'regex:/^[^@#$&*]+$/',
            ],
            'type' => [
                'required',
                'string',
                // regex: not allow special characters (@, #, $, &, *)
                'regex:/^[^@#$&*]+$/',
            ],
            'location' => [
                'required',
                'string',
                // regex: not allow special characters (@, #, $, &, *)
                'regex:/^[^@#$&*]+$/',
            ],
            'min_salary' => [
                'required',
                'numeric',
            ],
            'max_salary' => [
                'required',
                'numeric',
                'gte:min_salary'
            ],
            'recruit_num' => [
                'required',
                'numeric',
            ],
            'deadline' => [
                'required',
                'date',
            ],
            'position' => [
                'required',
                'string',
                // regex: not allow special characters (@, #, $, &, *)
                'regex:/^[^@#$&*]+$/',
            ],
            'min_yoe' => [
                'required',
                'numeric',
            ],
            'max_yoe' => [
                'required',
                'numeric',
                'gte:min_yoe',
            ],
            'gender' => [
                'string',
                // regex: not allow special characters (@, #, $, &, *)
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
            'title.required' => 'Bắt buộc phải nhập tiêu đề',
            'title.string' => 'Tiêu đề phải là chuỗi ký tự',
            'title.min' => 'Tiêu đề phải có ít nhất 10 ký tự',
            'title.regex' => 'Tiêu đề không được chứa ký tự đặc biệt (@, #, $, &, *)',

            'description.required' => 'Bắt buộc phải nhập mô tả',
            'description.string' => 'Mô tả phải là chuỗi ký tự',
            'description.min' => 'Mô tả phải có ít nhất 10 ký tự',
            'description.regex' => 'Mô tả không được chứa ký tự đặc biệt (@, #, $, &, *)',

            'benefit.required' => 'Bắt buộc phải nhập quyền lợi',
            'benefit.string' => 'Quyền lợi phải là chuỗi ký tự',
            'benefit.min' => 'Quyền lợi phải có ít nhất 10 ký tự',
            'benefit.regex' => 'Quyền lợi không được chứa ký tự đặc biệt (@, #, $, &, *)',

            'requirement.required' => 'Bắt buộc phải nhập yêu cầu',
            'requirement.string' => 'Yêu cầu phải là chuỗi ký tự',
            'requirement.regex' => 'Yêu cầu không được chứa ký tự đặc biệt (@, #, $, &, *)',

            'type.required' => 'Bắt buộc phải nhập loại công việc',
            'type.string' => 'Loại công việc phải là chuỗi ký tự',
            'type.regex' => 'Loại công việc không được chứa ký tự đặc biệt (@, #, $, &, *)',

            'location.required' => 'Bắt buộc phải nhập địa điểm',
            'location.string' => 'Địa điểm phải là chuỗi ký tự',
            'location.regex' => 'Địa điểm không được chứa ký tự đặc biệt (@, #, $, &, *)',

            'min_salary.required' => 'Bắt buộc phải nhập mức lương tối thiểu',
            'min_salary.numeric' => 'Mức lương tối thiểu phải là số',

            'max_salary.required' => 'Bắt buộc phải nhập mức lương tối đa',
            'max_salary.numeric' => 'Mức lương tối đa phải là số',
            'max_salary.gte' => 'Mức lương tối đa phải lớn hơn hoặc bằng mức lương tối thiểu',

            'recruit_num.required' => 'Bắt buộc phải nhập số lượng tuyển dụng',
            'recruit_num.numeric' => 'Số lượng tuyển dụng phải là số',

            'deadline.required' => 'Bắt buộc phải nhập hạn nộp hồ sơ',
            'deadline.date' => 'Hạn nộp hồ sơ phải là ngày tháng',

            'position.required' => 'Bắt buộc phải nhập vị trí tuyển dụng',
            'position.string' => 'Vị trí tuyển dụng phải là chuỗi ký tự',
            'position.regex' => 'Vị trí tuyển dụng không được chứa ký tự đặc biệt (@, #, $, &, *)',

            'min_yoe.required' => 'Bắt buộc phải nhập số năm kinh nghiệm tối thiểu',
            'min_yoe.numeric' => 'Số năm kinh nghiệm tối thiểu phải là số',

            'max_yoe.required' => 'Bắt buộc phải nhập số năm kinh nghiệm tối đa',
            'max_yoe.numeric' => 'Số năm kinh nghiệm tối đa phải là số',
            'max_yoe.gte' => 'Số năm kinh nghiệm tối đa phải lớn hơn hoặc bằng số năm kinh nghiệm tối thiểu',

            'gender.string' => 'Giới tính phải là chuỗi kí tự',
            'gender.regex' => 'Giới tính không được chứa ký tự đặc biệt (@, #, $, &, *)',
        ];
    }
}
