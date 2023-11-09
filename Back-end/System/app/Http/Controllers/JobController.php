<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateJobRequest;
use App\Http\Requests\UpdateJobRequest;
use App\Models\EmployerProfile;
use App\Models\Job;
use App\Models\SavedJob;
use App\Models\UserAccount;
use App\Models\UserHistory;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class JobController extends ApiController
{
    /**
     *  @OA\Get(
     *      path="/api/jobs",
     *      summary="Get all jobs",
     *      tags={"Jobs"},
     *      @OA\Parameter(
     *          name="count_per_page",
     *          in="query",
     *          description="Number of jobs per page",
     *          required=false
     *      ),
     *      @OA\Parameter(
     *          name="Accept",
     *          in="header",
     *          description="application/json",
     *          required=false
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Successfully retrieved jobs",
     *          @OA\JsonContent(
     *              example=
    {
    "error": false,
    "message": "Xử lí thành công",
    "data": {
    "jobs": {
    "current_page": 1,
    "data": {
    {
    "id": 1,
    "employer_id": 4,
    "title": "Trợ Lý Trưởng Phòng Xuất Nhập Khẩu",
    "description": "- Tư vấn tính năng, tiện ích và bán các sản phẩm điện thoại, máy tính bảng, Macbook tại Showroom. Không phải đi thị trường.- Phối hợp cùng team Marketing lên kế hoạch triển khai các Event hàng Tuần, Tháng và chương trình Chăm sóc sau Bán Hàng.- Các công việc khác được giao từ Quản lý. ",
    "benefit": "- Lương thỏa thuận (Tùy theo năng lực và kinh nghiệm). Ngoài ra còn chính sách thưởng hiệu quả làm việc.- Thưởng đột xuất theo thành tích đặc biệt và hoặc các sáng kiến cải tiến trong công việc.- Được hưởng đầy đủ quyền lợi của người lao động theo luật hiện hành (Bảo hiểm xã hội, Bảo hiểm y tế).- Được hưởng chế độ du lịch cùng Team, thưởng lễ Tết, thưởng theo doanh số kinh doanh của Công Ty.- Được tham gia đào tạo nâng cao chuyên sâu, chuyên môn và kỹ năng.- Cơ hội phát triển bản thân và thăng tiến trong tổ chức.- Môi trường làm việc năng động, thân thiện. Có cơ hội làm việc với nhiều đối tác lớn, uy tín.- Được hưởng năng suất hàng quý và tăng lương định kỳ. ",
    "requirement": "- Trợ lý Trưởng phòng Xuất nhập khẩu tối thiểu phải tốt nghiệp cử nhân ngành Kinh tế, Ngoại thương hoặc Kinh doanh quốc tế ngành Xuất nhập khẩu hoặc có kinh nghiệm từ 2 năm trở lên trong lĩnh vực này ở vị trí tương đương. Hoặc là dược sĩ và có kinh nghiệm làm việc ở vị trí tương đương.- Ưu tiên các ứng viên đã làm việc hoặc tiếp xúc với môi trường làm việc trong lĩnh vực dược phẩm (background kinh tế) hoặc công ty xuất nhật khẩu (background dược).- Trường hợp không đáp ứng toàn bộ MTCV và yêu cầu công việc nêu trên vẫn sẽ được đào tạo nhưng cần có ý chí mạnh mẽ, quyết tâm học việc, sự tập trung và khả năng chịu áp lực cao.- Có khả năng đàm phán và giao tiếp tốt, chịu áp lực công việc cao.- Có năng lực sắp xếp công việc, lên kế hoạch, báo cáo.- Có tiềm năng và hướng đến vị trí quản lý, điều hành, đưa ra được các đề xuất giúp phát triển phòng Xuất nhập khẩu tiến xa hơn và gắn với chiến lược công ty.- Có khả năng gắn kết, quan tâm, đánh giá và phát triển nguồn nhân lực trong phòng ban phục vụ cho sự phát triển của bản thân mỗi người, công việc và công ty theo giá trị cốt lõi của công ty.- Có kỹ năng sử dụng tiếng Anh, đặc biệt là kỹ năng viết tốt.- Có kỹ năng phân tích, tổng hợp tốt, đánh giá và đề xuất, tham mưu cho Hội đồng thành viên.- Có kỹ năng thuyết trình trước đám đông một cách rõ ràng, dễ hiểu, đạt được hiệu quả cao nhất.- Quyết đoán trong công việc, dám nghĩ dám làm, dám chịu trách nhiệm.- Có phẩm chất đạo đức tốt và trung thực. ",
    "min_salary": 1,
    "max_salary": 5,
    "recruit_num": 1,
    "position": "Toàn thời gian",
    "min_yoe": 0,
    "max_yoe": 2,
    "deadline": "2024-09-01",
    "job_locations": {
    {
    "id": 1,
    "job_id": 1,
    "location": "Hồ Chí Minh: 72 Bình Giã"
    }
    },
    "job_skills": {
    {
    "id": 1,
    "job_id": 1,
    "skill": "PowerPoint"
    },
    {
    "id": 2,
    "job_id": 1,
    "skill": "Microsoft Excel"
    },
    {
    "id": 3,
    "job_id": 1,
    "skill": "Microsoft Word"
    }
    },
    "job_types": {
    {
    "id": 1,
    "job_id": 1,
    "type": "Nhân viên"
    }
    },
    "employer_profile": {
    "id": 4,
    "company_id": 5,
    "full_name": "Trinh Minh Sang",
    "avatar": "https://i.imgur.com/hepj9ZS.png",
    "company_profile": {
    "id": 5,
    "name": "CÔNG TY TNHH TRIỆU ĐIỀN",
    "logo": "https://i.imgur.com/hepj9ZS.png",
    "description": "none",
    "site": "không có",
    "address": "Tòa Ruby 1, Giang Biên, Long Biên, Hà Nội",
    "size": "25-99"
    }
    }
    }
    },
    "first_page_url": "http://localhost:8000/api/jobs?page=1",
    "from": 1,
    "last_page": 51,
    "last_page_url": "http://localhost:8000/api/jobs?page=51",
    "links": {
    {
    "url": null,
    "label": "&laquo; Previous",
    "active": false
    },
    {
    "url": "http://localhost:8000/api/jobs?page=1",
    "label": "1",
    "active": true
    },
    {
    "url": "http://localhost:8000/api/jobs?page=2",
    "label": "2",
    "active": false
    },
    {
    "url": "http://localhost:8000/api/jobs?page=3",
    "label": "3",
    "active": false
    },
    {
    "url": "http://localhost:8000/api/jobs?page=4",
    "label": "4",
    "active": false
    },
    {
    "url": "http://localhost:8000/api/jobs?page=5",
    "label": "5",
    "active": false
    },
    {
    "url": "http://localhost:8000/api/jobs?page=6",
    "label": "6",
    "active": false
    },
    {
    "url": "http://localhost:8000/api/jobs?page=7",
    "label": "7",
    "active": false
    },
    {
    "url": "http://localhost:8000/api/jobs?page=8",
    "label": "8",
    "active": false
    },
    {
    "url": "http://localhost:8000/api/jobs?page=9",
    "label": "9",
    "active": false
    },
    {
    "url": "http://localhost:8000/api/jobs?page=10",
    "label": "10",
    "active": false
    },
    {
    "url": null,
    "label": "...",
    "active": false
    },
    {
    "url": "http://localhost:8000/api/jobs?page=50",
    "label": "50",
    "active": false
    },
    {
    "url": "http://localhost:8000/api/jobs?page=51",
    "label": "51",
    "active": false
    },
    {
    "url": "http://localhost:8000/api/jobs?page=2",
    "label": "Next &raquo;",
    "active": false
    }
    },
    "next_page_url": "http://localhost:8000/api/jobs?page=2",
    "path": "http://localhost:8000/api/jobs",
    "per_page": 1,
    "prev_page_url": null,
    "to": 1,
    "total": 51
    }
    },
    "status_code": 200
    }
     *          )
     *      ),
     *      @OA\Response(
     *          response=404,
     *          description="No jobs found",
     *          ref="#/components/responses/NotFound"
     *      ),
     *  )
     */
    public function getJobs(Request $request): JsonResponse
    {
        try {
            $count_per_page = $request->count_per_page ?? 10;
            $order_by = $request->order_by ?? 'id';
            $order_type = $request->order_type ?? 'asc';

            $jobs = Job::filter($request, Job::query())
                ->with('skills', 'employer_profile.company_profile', 'categories')
                ->orderBy($order_by, $order_type);

            if ($count_per_page < 1) {
                $jobs = $jobs->get();
            } else {
                $jobs = $jobs->paginate($count_per_page);
            }

            if (count($jobs) === 0) {
                return $this->respondNotFound();
            }

            $user = auth('sanctum')->check() ? auth('sanctum')->user() : null;

            // check if each job is saved by user and add to job
            if ($user && $user->tokenCan('user')) {
                foreach ($jobs as $job) {
                    $user_saved_job = SavedJob::where('user_id', $user->id)->where('job_id', $job->id)->first();
                    $job->is_saved = (bool)$user_saved_job;
                }
            }

            return $this->respondWithData(
                [
                    'jobs' => $jobs,
                ]);
        }
        catch (Exception $exception) {
            return $this->respondInternalServerError($exception->getMessage());
        }
    }

    /**
     *  @OA\Get(
     *      path="/api/jobs/{id}",
     *      tags={"Jobs"},
     *      summary="Get job by id",
     *      @OA\Parameter(
     *          name="employer_id",
     *          description="Employer id",
     *          required=true,
     *          in="path",
     *      ),
     *      @OA\Parameter(
     *          name="Accept",
     *          in="header",
     *          description="application/json",
     *          required=false
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Job retrieved successfully",
     *          @OA\JsonContent(
     *              example=
    {
    "error": false,
    "message": "Xử lí thành công",
    "data": {
    "job": {
    "id": 1,
    "employer_id": 4,
    "title": "Trợ Lý Trưởng Phòng Xuất Nhập Khẩu",
    "description": "- Tư vấn tính năng, tiện ích và bán các sản phẩm điện thoại, máy tính bảng, Macbook tại Showroom. Không phải đi thị trường.- Phối hợp cùng team Marketing lên kế hoạch triển khai các Event hàng Tuần, Tháng và chương trình Chăm sóc sau Bán Hàng.- Các công việc khác được giao từ Quản lý. ",
    "benefit": "- Lương thỏa thuận (Tùy theo năng lực và kinh nghiệm). Ngoài ra còn chính sách thưởng hiệu quả làm việc.- Thưởng đột xuất theo thành tích đặc biệt và hoặc các sáng kiến cải tiến trong công việc.- Được hưởng đầy đủ quyền lợi của người lao động theo luật hiện hành (Bảo hiểm xã hội, Bảo hiểm y tế).- Được hưởng chế độ du lịch cùng Team, thưởng lễ Tết, thưởng theo doanh số kinh doanh của Công Ty.- Được tham gia đào tạo nâng cao chuyên sâu, chuyên môn và kỹ năng.- Cơ hội phát triển bản thân và thăng tiến trong tổ chức.- Môi trường làm việc năng động, thân thiện. Có cơ hội làm việc với nhiều đối tác lớn, uy tín.- Được hưởng năng suất hàng quý và tăng lương định kỳ. ",
    "requirement": "- Trợ lý Trưởng phòng Xuất nhập khẩu tối thiểu phải tốt nghiệp cử nhân ngành Kinh tế, Ngoại thương hoặc Kinh doanh quốc tế ngành Xuất nhập khẩu hoặc có kinh nghiệm từ 2 năm trở lên trong lĩnh vực này ở vị trí tương đương. Hoặc là dược sĩ và có kinh nghiệm làm việc ở vị trí tương đương.- Ưu tiên các ứng viên đã làm việc hoặc tiếp xúc với môi trường làm việc trong lĩnh vực dược phẩm (background kinh tế) hoặc công ty xuất nhật khẩu (background dược).- Trường hợp không đáp ứng toàn bộ MTCV và yêu cầu công việc nêu trên vẫn sẽ được đào tạo nhưng cần có ý chí mạnh mẽ, quyết tâm học việc, sự tập trung và khả năng chịu áp lực cao.- Có khả năng đàm phán và giao tiếp tốt, chịu áp lực công việc cao.- Có năng lực sắp xếp công việc, lên kế hoạch, báo cáo.- Có tiềm năng và hướng đến vị trí quản lý, điều hành, đưa ra được các đề xuất giúp phát triển phòng Xuất nhập khẩu tiến xa hơn và gắn với chiến lược công ty.- Có khả năng gắn kết, quan tâm, đánh giá và phát triển nguồn nhân lực trong phòng ban phục vụ cho sự phát triển của bản thân mỗi người, công việc và công ty theo giá trị cốt lõi của công ty.- Có kỹ năng sử dụng tiếng Anh, đặc biệt là kỹ năng viết tốt.- Có kỹ năng phân tích, tổng hợp tốt, đánh giá và đề xuất, tham mưu cho Hội đồng thành viên.- Có kỹ năng thuyết trình trước đám đông một cách rõ ràng, dễ hiểu, đạt được hiệu quả cao nhất.- Quyết đoán trong công việc, dám nghĩ dám làm, dám chịu trách nhiệm.- Có phẩm chất đạo đức tốt và trung thực. ",
    "min_salary": 1,
    "max_salary": 5,
    "recruit_num": 1,
    "position": "Toàn thời gian",
    "min_yoe": 0,
    "max_yoe": 2,
    "deadline": "2024-09-01",
    "job_locations": {
    {
    "id": 1,
    "job_id": 1,
    "location": "Hồ Chí Minh: 72 Bình Giã"
    }
    },
    "job_skills": {
    {
    "id": 1,
    "job_id": 1,
    "skill": "PowerPoint"
    },
    {
    "id": 2,
    "job_id": 1,
    "skill": "Microsoft Excel"
    },
    {
    "id": 3,
    "job_id": 1,
    "skill": "Microsoft Word"
    }
    },
    "job_types": {
    {
    "id": 1,
    "job_id": 1,
    "type": "Nhân viên"
    }
    },
    "employer_profile": {
    "id": 4,
    "company_id": 5,
    "full_name": "Trinh Minh Sang",
    "avatar": "https://i.imgur.com/hepj9ZS.png",
    "company_profile": {
    "id": 5,
    "name": "CÔNG TY TNHH TRIỆU ĐIỀN",
    "logo": "https://i.imgur.com/hepj9ZS.png",
    "description": "none",
    "site": "không có",
    "address": "Tòa Ruby 1, Giang Biên, Long Biên, Hà Nội",
    "size": "25-99"
    }
    }
    }
    },
    "status_code": 200
    }
     *          ),
     *      ),
     *      @OA\Response(
     *          response=404,
     *          description="No job found",
     *          ref="#/components/responses/NotFound"
     *      ),
     *  )
     */
    public function getJobById(Request $request, string $id): JsonResponse
    {
        try {
            $job = Job::with('skills', 'employer_profile.company_profile', 'categories')
                ->find($id);

            if (!$job) {
                return $this->respondNotFound();
            }

            $user = auth('sanctum')->check() ? auth('sanctum')->user() : null;

            $job->is_saved = false;
            if ($user && $user->tokenCan('user')) {
                $user_history = UserHistory::where('user_id', $user->id)->where('job_id', $id)->first();
                if ($user_history) {
                    $user_history->times = $user_history->times + 1;
                }
                else {
                    $user_history = new UserHistory();
                    $user_history->user_id = $user->id;
                    $user_history->job_id = $id;
                    $user_history->times = 1;
                }
                $user_history->save();

                $user_saved_job = SavedJob::where('user_id', $user->id)->where('job_id', $id)->first();
                $job->is_saved = (bool)$user_saved_job;
            }

            return $this->respondWithData(
                [
                    'job' => $job,
                ]);
        }
        catch (Exception $exception) {
            return $this->respondInternalServerError($exception->getMessage());
        }
    }

    /**
     * @OA\Post(
     *      path="/api/jobs",
     *      tags={"Jobs"},
     *      summary="Create a new job",
     *      description="Returns the job data",
     *      @OA\Parameter(
     *          name="Accept",
     *          in="header",
     *          description="application/json",
     *          required=false
     *      ),
     *      @OA\Parameter(
     *          name="Authorization",
     *          in="header",
     *          description="Bearer {token}",
     *          required=true
     *      ),
     *      @OA\RequestBody(
     *          required=true,
     *          @OA\JsonContent(
     *              example=
    {
    "title": "job title",
    "description": "job des",
    "benefit": "job ben",
    "requirement": "job req",
    "min_salary": "1",
    "max_salary": "2",
    "recruit_num": "3",
    "position": "pos",
    "year_of_experience": "2",
    "deadline": "2023-09-09",
    }
     *          )
     *      ),
     *      @OA\Response(
     *          response=201,
     *          description="Successfully created job",
     *          @OA\JsonContent(
     *              example=
    {
    "error": false,
    "message": "Xử lí thành công",
    "data": {
    "job": {
    "employer_id": 1,
    "title": "job title",
    "description": "job des",
    "benefit": "job ben",
    "requirement": "job req",
    "min_salary": "1",
    "max_salary": "2",
    "recruit_num": "3",
    "position": "pos",
    "year_of_experience": "2",
    "deadline": "2023-09-09",
    "id": 51
    }
    },
    "status_code": 200
    }
     *          )
     *      ),
     *      @OA\Response(
     *          response=404,
     *          description="No job found",
     *          ref="#/components/responses/NotFound"
     *      ),
     *  )
     */
    public function createJob(CreateJobRequest $request): JsonResponse
    {
        try {
            $job = new Job();

            if ($request->user()->tokenCan('employer')) {
                $job->employer_id = $request->user()->id;
            }
            else {
                $temp_employer = EmployerProfile::where('company_id', $request->user()->id)->first();

                if (!$temp_employer)
                    return $this->respondBadRequest('Không tìm được thông tin tài khoản nhân viên');

                $job->employer_id = $temp_employer->id;
            }

            $job->title = $request->validated()['title'];
            $job->description = $request->validated()['description'];
            $job->benefit = $request->validated()['benefit'];
            $job->requirement = $request->validated()['requirement'];
            $job->type = $request->validated()['type'];
            $job->location = $request->validated()['location'];
            $job->min_salary = $request->validated()['min_salary'];
            $job->max_salary = $request->validated()['max_salary'];
            $job->recruit_num = $request->validated()['recruit_num'];
            $job->position = $request->validated()['position'];
            $job->min_yoe = $request->validated()['min_yoe'];
            $job->max_yoe = $request->validated()['max_yoe'];
            $job->gender = $request->validated()['gender'] ?? 'Không yêu cầu';
            $job->deadline = $request->validated()['deadline'];

            $job->save();

            return $this->respondWithData(
                [
                    'job' => $job,
                ]);
        }
        catch (Exception $exception) {
            return $this->respondInternalServerError($exception->getMessage());
        }
    }

    /**
     * @OA\Put(
     *      path="/api/jobs/{id}",
     *      tags={"Jobs"},
     *      summary="Update a job",
     *      @OA\Parameter(
     *          name="id",
     *          description="Job id",
     *          required=true,
     *          in="path",
     *      ),
     *      @OA\Parameter(
     *          name="Accept",
     *          in="header",
     *          description="application/json",
     *          required=false
     *      ),
     *      @OA\Parameter(
     *          name="Authorization",
     *          in="header",
     *          description="Bearer {token}",
     *          required=true
     *      ),
     *      @OA\RequestBody(
     *          required=true,
     *          @OA\JsonContent(
     *              example=
    {
    "title": "job title",
    "description": "job des",
    "benefit": "job ben",
    "requirement": "job req",
    "min_salary": "1",
    "max_salary": "2",
    "recruit_num": "3",
    "position": "pos",
    "year_of_experience": "2",
    "deadline": "2023-09-09",
    }
     *          )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Successfully updated job",
     *          @OA\JsonContent(
     *              example=
    {
    "error": false,
    "message": "Xử lí thành công",
    "data": {
    "job": {
    "id": 51,
    "employer_id": 1,
    "title": "titletasdasd",
    "description": "job des",
    "benefit": "job ben",
    "requirement": "job req",
    "min_salary": 1,
    "max_salary": 2,
    "recruit_num": 3,
    "position": "pos",
    "year_of_experience": "2",
    "deadline": "2023-09-09",
    }
    },
    "status_code": 200
    }
     *          )
     *      ),
     *      @OA\Response(
     *          response=404,
     *          description="No job found",
     *          ref="#/components/responses/NotFound"
     *      ),
     *  )
     */
    public function updateJob(UpdateJobRequest $request, string $id): JsonResponse
    {
        try {
            $job = Job::where('id', $id)->first();

            if (!$job) {
                return $this->respondNotFound();
            }

            if ($request->user()->tokenCan('company')) {
                $temp_employer = EmployerProfile::where('id', $job->employer_id)->first();
                if ($temp_employer->company_id != $request->user()->id) {
                    return $this->respondForbidden('Bạn không có quyền chỉnh sửa thông tin này');
                }
            }

            if ($job->employer_id != $request->user()->id) {
                return $this->respondForbidden('Bạn không có quyền chỉnh sửa thông này');
            }

            $min_salary = $request->validated()['min_salary'] ?? $job->min_salary;
            $max_salary = $request->validated()['max_salary'] ?? $job->max_salary;
            $min_yoe = $request->validated()['min_yoe'] ?? $job->min_yoe;
            $max_yoe = $request->validated()['max_yoe'] ?? $job->max_yoe;

            if ($min_salary > $max_salary) {
                return $this->respondBadRequest('Mức lương tối thiểu không được lớn hơn mức lương tối đa');
            }

            if ($min_yoe > $max_yoe) {
                return $this->respondBadRequest('Kinh nghiệm tối thiểu không được lớn hơn kinh nghiệm tối đa');
            }

            $job->update($request->validated());

            return $this->respondWithData(
                [
                    'job' => $job,
                ]);
        }
        catch (Exception $exception) {
            return $this->respondInternalServerError($exception->getMessage());
        }
    }

    public function stopJob(string $id): JsonResponse
    {
        try {
            $job = Job::where('id', $id)->first();

            if (!$job) {
                return $this->respondNotFound();
            }

            $job->status = 'Ngừng tuyển';
            $job->save();

            return $this->respondWithData(
                [
                    'job' => $job,
                ]);
        }
        catch (Exception $exception) {
            return $this->respondInternalServerError($exception->getMessage());
        }
    }

    /**
     * @OA\Delete(
     *      path="/api/jobs/{id}",
     *      tags={"Jobs"},
     *      summary="Delete a job",
     *      description="Returns the job data",
     *      @OA\Parameter(
     *          name="id",
     *          description="Job id",
     *          required=true,
     *          in="path",
     *          example="1"
     *      ),
     *      @OA\Parameter(
     *          name="Accept",
     *          in="header",
     *          description="application/json",
     *          required=false
     *      ),
     *      @OA\Parameter(
     *          name="Authorization",
     *          in="header",
     *          description="Bearer {token}",
     *          required=true
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Successfully deleted job",
     *          @OA\JsonContent(
     *              example=
    {
    "error": false,
    "message": "Xoá thành công",
    "data": {
    "job": {
    "id": 52,
    "employer_id": 1,
    "title": "titlet",
    "description": "des",
    "benefit": "be",
    "requirement": "re",
    "min_salary": 1,
    "max_salary": 2,
    "recruit_num": 1,
    "position": "adsfa",
    "year_of_experience": "sfdasdf",
    "deadline": "2023-03-03",
    }
    },
    "status_code": 200
    }
     *          )
     *      ),
     *      @OA\Response(
     *          response=404,
     *          description="No job found",
     *          ref="#/components/responses/NotFound"
     *      ),
     *  )
     */
    public function deleteJob(string $id): JsonResponse
    {
        try {
            $job = Job::where('id', $id)->first();

            if (!$job) {
                return $this->respondNotFound();
            }

            $job->delete();

            $job->skills()->delete();
            $job->categories()->detach();

            return $this->respondWithData(
                [
                    'job' => $job,
                ], 'Xoá thành công');
        }
        catch (Exception $exception) {
            return $this->respondInternalServerError($exception->getMessage());
        }
    }
}
