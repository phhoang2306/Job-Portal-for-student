<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateApplicationRequest;
use App\Models\Application;
use App\Models\CompanyAccount;
use App\Models\CompanyProfile;
use App\Models\EmployerProfile;
use App\Models\Job;
use App\Models\TimeTable;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ApplicationController extends ApiController
{
    /**
     *  @OA\Get(
     *      path="/api/applications",
     *      summary="Get all applications",
     *      tags={"Applications"},
     *      security={{"sanctum":{}}},
     *      @OA\Parameter(
     *          name="count_per_page",
     *          in="query",
     *          description="Number of applications per page",
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
     *          description="Successfully retrieved applications",
     *          @OA\JsonContent(
     *              example=
    {
    "error": false,
    "message": "Xử lí thành công",
    "data": {
    "applications": {
    "current_page": 1,
    "data": {
    {
    "id": 1,
    "job_id": 1,
    "user_id": 2,
    "cv_id": 2,
    "status": "Đang chờ",
    "job": {
    "id": 1,
    "employer_id": 4,
    "title": "Trợ Lý Trưởng Phòng Xuất Nhập Khẩu",
    "description": "- Tư vấn tính năng, tiện ích và bán các sản phẩm điện thoại, máy tính bảng, Macbook tại Showroom. Không phải đi thị trường.- Phối hợp cùng team Marketing lên kế hoạch triển khai các Event hàng Tuần, Tháng và chương trình Chăm sóc sau Bán Hàng.- Các công việc khác được giao từ Quản lý. ",
    "benefit": "- Lương thỏa thuận (Tùy theo năng lực và kinh nghiệm). Ngoài ra còn chính sách thưởng hiệu quả làm việc.- Thưởng đột xuất theo thành tích đặc biệt và hoặc các sáng kiến cải tiến trong công việc.- Được hưởng đầy đủ quyền lợi của người lao động theo luật hiện hành (Bảo hiểm xã hội, Bảo hiểm y tế).- Được hưởng chế độ du lịch cùng Team, thưởng lễ Tết, thưởng theo doanh số kinh doanh của Công Ty.- Được tham gia đào tạo nâng cao chuyên sâu, chuyên môn và kỹ năng.- Cơ hội phát triển bản thân và thăng tiến trong tổ chức.- Môi trường làm việc năng động, thân thiện. Có cơ hội làm việc với nhiều đối tác lớn, uy tín.- Được hưởng năng suất hàng quý và tăng lương định kỳ. ",
    "requirement": "- Trợ lý Trưởng phòng Xuất nhập khẩu tối thiểu phải tốt nghiệp cử nhân ngành Kinh tế, Ngoại thương hoặc Kinh doanh quốc tế ngành Xuất nhập khẩu hoặc có kinh nghiệm từ 2 năm trở lên trong lĩnh vực này ở vị trí tương đương. Hoặc là dược sĩ và có kinh nghiệm làm việc ở vị trí tương đương.- Ưu tiên các ứng viên đã làm việc hoặc tiếp xúc với môi trường làm việc trong lĩnh vực dược phẩm (background kinh tế) hoặc công ty xuất nhật khẩu (background dược).- Trường hợp không đáp ứng toàn bộ MTCV và yêu cầu công việc nêu trên vẫn sẽ được đào tạo nhưng cần có ý chí mạnh mẽ, quyết tâm học việc, sự tập trung và khả năng chịu áp lực cao.- Có khả năng đàm phán và giao tiếp tốt, chịu áp lực công việc cao.- Có năng lực sắp xếp công việc, lên kế hoạch, báo cáo.- Có tiềm năng và hướng đến vị trí quản lý, điều hành, đưa ra được các đề xuất giúp phát triển phòng Xuất nhập khẩu tiến xa hơn và gắn với chiến lược công ty.- Có khả năng gắn kết, quan tâm, đánh giá và phát triển nguồn nhân lực trong phòng ban phục vụ cho sự phát triển của bản thân mỗi người, công việc và công ty theo giá trị cốt lõi của công ty.- Có kỹ năng sử dụng tiếng Anh, đặc biệt là kỹ năng viết tốt.- Có kỹ năng phân tích, tổng hợp tốt, đánh giá và đề xuất, tham mưu cho Hội đồng thành viên.- Có kỹ năng thuyết trình trước đám đông một cách rõ ràng, dễ hiểu, đạt được hiệu quả cao nhất.- Quyết đoán trong công việc, dám nghĩ dám làm, dám chịu trách nhiệm.- Có phẩm chất đạo đức tốt và trung thực. ",
    "min_salary": -1,
    "max_salary": -1,
    "recruit_num": 1,
    "position": "Toàn thời gian",
    "year_of_experience": "2",
    "deadline": "2024-09-01",
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
    },
    "user_profile": {
    "id": 2,
    "full_name": "NGO HONG CHAN",
    "avatar": "https://i.imgur.com/hepj9ZS.png",
    "about_me": "Giỏi giao tiếp, làm việc độc lập, làm việc nhóm, giải quyết vấn đề, Trách nhiệm cao, thân thiện, trung thực và chăm chỉ. Có thể làm việc dưới áp lực cao\n",
    "good_at_position": "Full-Stack Developer",
    "year_of_experience": "0",
    "date_of_birth": "2000-01-01",
    "gender": "Nam",
    "address": "TPHCM",
    "email": "ngohongchan12a4@gmail.com",
    "phone": "(+84)768729814"
    }
    },
    {
    "id": 2,
    "job_id": 1,
    "user_id": 4,
    "cv_id": 4,
    "status": "Đang chờ",
    "job": {
    "id": 1,
    "employer_id": 4,
    "title": "Trợ Lý Trưởng Phòng Xuất Nhập Khẩu",
    "description": "- Tư vấn tính năng, tiện ích và bán các sản phẩm điện thoại, máy tính bảng, Macbook tại Showroom. Không phải đi thị trường.- Phối hợp cùng team Marketing lên kế hoạch triển khai các Event hàng Tuần, Tháng và chương trình Chăm sóc sau Bán Hàng.- Các công việc khác được giao từ Quản lý. ",
    "benefit": "- Lương thỏa thuận (Tùy theo năng lực và kinh nghiệm). Ngoài ra còn chính sách thưởng hiệu quả làm việc.- Thưởng đột xuất theo thành tích đặc biệt và hoặc các sáng kiến cải tiến trong công việc.- Được hưởng đầy đủ quyền lợi của người lao động theo luật hiện hành (Bảo hiểm xã hội, Bảo hiểm y tế).- Được hưởng chế độ du lịch cùng Team, thưởng lễ Tết, thưởng theo doanh số kinh doanh của Công Ty.- Được tham gia đào tạo nâng cao chuyên sâu, chuyên môn và kỹ năng.- Cơ hội phát triển bản thân và thăng tiến trong tổ chức.- Môi trường làm việc năng động, thân thiện. Có cơ hội làm việc với nhiều đối tác lớn, uy tín.- Được hưởng năng suất hàng quý và tăng lương định kỳ. ",
    "requirement": "- Trợ lý Trưởng phòng Xuất nhập khẩu tối thiểu phải tốt nghiệp cử nhân ngành Kinh tế, Ngoại thương hoặc Kinh doanh quốc tế ngành Xuất nhập khẩu hoặc có kinh nghiệm từ 2 năm trở lên trong lĩnh vực này ở vị trí tương đương. Hoặc là dược sĩ và có kinh nghiệm làm việc ở vị trí tương đương.- Ưu tiên các ứng viên đã làm việc hoặc tiếp xúc với môi trường làm việc trong lĩnh vực dược phẩm (background kinh tế) hoặc công ty xuất nhật khẩu (background dược).- Trường hợp không đáp ứng toàn bộ MTCV và yêu cầu công việc nêu trên vẫn sẽ được đào tạo nhưng cần có ý chí mạnh mẽ, quyết tâm học việc, sự tập trung và khả năng chịu áp lực cao.- Có khả năng đàm phán và giao tiếp tốt, chịu áp lực công việc cao.- Có năng lực sắp xếp công việc, lên kế hoạch, báo cáo.- Có tiềm năng và hướng đến vị trí quản lý, điều hành, đưa ra được các đề xuất giúp phát triển phòng Xuất nhập khẩu tiến xa hơn và gắn với chiến lược công ty.- Có khả năng gắn kết, quan tâm, đánh giá và phát triển nguồn nhân lực trong phòng ban phục vụ cho sự phát triển của bản thân mỗi người, công việc và công ty theo giá trị cốt lõi của công ty.- Có kỹ năng sử dụng tiếng Anh, đặc biệt là kỹ năng viết tốt.- Có kỹ năng phân tích, tổng hợp tốt, đánh giá và đề xuất, tham mưu cho Hội đồng thành viên.- Có kỹ năng thuyết trình trước đám đông một cách rõ ràng, dễ hiểu, đạt được hiệu quả cao nhất.- Quyết đoán trong công việc, dám nghĩ dám làm, dám chịu trách nhiệm.- Có phẩm chất đạo đức tốt và trung thực. ",
    "min_salary": -1,
    "max_salary": -1,
    "recruit_num": 1,
    "position": "Toàn thời gian",
    "year_of_experience": "2",
    "deadline": "2024-09-01",
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
    },
    "user_profile": {
    "id": 4,
    "full_name": "BÙI ANH THƯ",
    "avatar": "https://i.imgur.com/hepj9ZS.png",
    "about_me": "Tôi là một lập trình viên frontend với gần 2 năm kinh nghiệm về Reactjs, Javascript. Tôi luôn thích học hỏi các công nghệ mới, sẵn sàng giải quyết các vấn đề trong Web. Mong muốn trở thành lập trình viên Fullstack trong 2 năm tới",
    "good_at_position": "Frontend Developer",
    "year_of_experience": "2",
    "date_of_birth": "2001-01-01",
    "gender": "Nữ",
    "address": "TPHCM",
    "email": "thuanhbui2411@gmail.com",
    "phone": "(+84)989713105"
    }
    }
    },
    "first_page_url": "http://localhost:8000/api/applications?page=1",
    "from": 1,
    "last_page": 3,
    "last_page_url": "http://localhost:8000/api/applications?page=3",
    "links": {
    {
    "url": null,
    "label": "&laquo; Previous",
    "active": false
    },
    {
    "url": "http://localhost:8000/api/applications?page=1",
    "label": "1",
    "active": true
    },
    {
    "url": "http://localhost:8000/api/applications?page=2",
    "label": "2",
    "active": false
    },
    {
    "url": "http://localhost:8000/api/applications?page=3",
    "label": "3",
    "active": false
    },
    {
    "url": "http://localhost:8000/api/applications?page=2",
    "label": "Next &raquo;",
    "active": false
    }
    },
    "next_page_url": "http://localhost:8000/api/applications?page=2",
    "path": "http://localhost:8000/api/applications",
    "per_page": 2,
    "prev_page_url": null,
    "to": 2,
    "total": 6
    }
    },
    "status_code": 200
    }
     *          ),
     *      ),
     *      @OA\Response(
     *          response=404,
     *          description="No applications found",
     *          ref="#/components/responses/NotFound"
     *      ),
     *  ),
     */
    public function getApplications(Request $request): JsonResponse
    {
        try {
            $count_per_page = $request->count_per_page ?? 10;
            $order_by = $request->order_by ?? 'id';
            $order_type = $request->order_type ?? 'asc';

            // get all applications,

            $applications = Application::filter($request, Application::query())
                ->with(['job.employer_profile.company_profile', 'user_profile', 'cv'])
                ->orderBy($order_by, $order_type)
                ->paginate($count_per_page);

            if (count($applications) === 0) {
                return $this->respondNotFound();
            }

            return $this->respondWithData(
                [
                    'applications' => $applications,
                ]);
        }
        catch (Exception $exception) {
            return $this->respondInternalServerError($exception->getMessage());
        }
    }

    /**
     *  @OA\Get(
     *      path="/api/applications/{id}",
     *      summary="Get application by id",
     *      tags={"Applications"},
     *      security={{"sanctum":{}}},
     *      @OA\Parameter(
     *          name="id",
     *          in="path",
     *          description="Application id",
     *          required=true
     *      ),
     *      @OA\Parameter(
     *          name="count_per_page",
     *          in="query",
     *          description="Number of applications per page",
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
     *          description="Successfully retrieved application",
     *          @OA\JsonContent(
     *              example=
    {
    "error": false,
    "message": "Xử lí thành công",
    "data": {
    "application": {
    "id": 1,
    "job_id": 1,
    "user_id": 2,
    "cv_id": 2,
    "status": "Đang chờ",
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
    },
    "user_profile": {
    "id": 2,
    "full_name": "NGO HONG CHAN",
    "avatar": "https://i.imgur.com/hepj9ZS.png",
    "about_me": "Giỏi giao tiếp, làm việc độc lập, làm việc nhóm, giải quyết vấn đề, Trách nhiệm cao, thân thiện, trung thực và chăm chỉ. Có thể làm việc dưới áp lực cao\n",
    "good_at_position": "Full-Stack Developer",
    "year_of_experience": "0",
    "date_of_birth": "2000-01-01",
    "gender": "Nam",
    "address": "TPHCM",
    "email": "ngohongchan12a4@gmail.com",
    "phone": "(+84)768729814"
    }
    }
    },
    "status_code": 200
    }
     *          ),
     *      ),
     *      @OA\Response(
     *          response=404,
     *          description="No applications found",
     *          ref="#/components/responses/NotFound"
     *      ),
     *  )
     */
    public function getApplicationById(string $id): JsonResponse
    {
        try {
            $application = Application::where('id', $id)
            ->with(['job.employer_profile.company_profile', 'user_profile', 'cv'])
            ->first();

            if (!$application) {
                return $this->respondNotFound();
            }

            return $this->respondWithData(
                [
                    'application' => $application,
                ]);
        }
        catch (Exception $exception) {
            return $this->respondInternalServerError($exception->getMessage());
        }
    }

    /**
     *  @OA\Post(
     *      path="/api/applications",
     *      summary="Create new application",
     *      tags={"Applications"},
     *      security={{"sanctum":{}}},
     *      @OA\Parameter(
     *          name="Accept",
     *          in="header",
     *          description="application/json",
     *          required=false
     *      ),
     *      @OA\RequestBody(
     *          required=true,
     *          description="Application data",
     *          @OA\JsonContent(
     *              example = {
                        "job_id": 1,
                        "user_id": 2,
                        "cv_id": 2,
                        "status": "đang chờ"
                    }
     *          ),
     *      ),
     *      @OA\Response(
     *          response=201,
     *          description="Successfully created application",
     *          @OA\JsonContent(
     *              example = {
                        "error": false,
                        "message": "Xử lí thành công",
                        "data": {
                            "application": {
                            "id": 1,
                            "job_id": 1,
                            "user_id": 2,
                            "cv_id": 2,
                            "status": "Đang chờ",
                            "deleted_at": null
                            }
                        },
                        "status_code": 201
                    }
     *          ),
     *      ),
     *      @OA\Response(
     *          response=404,
     *          description="No applications found",
     *          ref="#/components/responses/NotFound"
     *      ),
     *  )
     */
    public function createApplication(CreateApplicationRequest $request): JsonResponse
    {
        try {
            $application = Application::where('job_id', $request->validated()['job_id'])
                ->where('user_id', $request->user()->id)
                ->first();

            if ($application && $application->status !== 'Đã từ chối') {
                return $this->respondForbidden('Bạn đã nộp đơn cho công việc này');
            }

            $application = new Application();
            $application->job_id = $request->validated()['job_id'];
            $application->user_id = $request->user()->id;
            $application->cv_id = $request->validated()['cv_id'];

            if ($request->select_timetable === 'false' || !$request->select_timetable) {
                $application->time_table = null;
            }
            else {
                $time_table = TimeTable::where('user_id', $request->user()->id)->first();

                if (!$time_table) {
                    return $this->respondNotFound('Không tìm thấy thời khóa biểu của bạn');
                }

                $coordinate = $time_table->coordinate;
                $application->time_table = $coordinate;

            }

            $application->save();

            return $this->respondCreated(
                [
                    'application' => $application,
                ]);
        }
        catch (Exception $exception) {
            return $this->respondInternalServerError($exception->getMessage());
        }
    }

    /**
     *  @OA\Put(
     *      path="/api/applications/approve/{id}",
     *      summary="Approve application",
     *      tags={"Applications"},
     *      security={{"sanctum":{}}},
     *      @OA\Parameter(
     *          name="id",
     *          in="path",
     *          description="Application id",
     *          required=true
     *      ),
     *      @OA\Parameter(
     *          name="Accept",
     *          in="header",
     *          description="application/json",
     *          required=false
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Successfully updated application",
     *          @OA\JsonContent(
     *              example = {
                        "error": false,
                        "message": "Xử lí thành công",
                        "data": {
                            "application": {
                            "id": 1,
                            "job_id": 1,
                            "user_id": 2,
                            "cv_id": 2,
                            "status": "Đã duyệt",
                            "deleted_at": null
                            }
                        },
                        "status_code": 200
                    }
     *          ),
     *      ),
     *      @OA\Response(
     *          response=404,
     *          description="No applications found",
     *          ref="#/components/responses/NotFound"
     *      ),
     *  )
     */
    public function approveApplication(Request $request, string $id): JsonResponse
    {
        try {
            $application = Application::where('id', $id)->first();

            if (!$application) {
                return $this->respondNotFound();
            }

            $job = Job::where('id', $application->job_id)->first();
            $employer_profile = EmployerProfile::where('id', $job->employer_id)->first();
            $request_profile = EmployerProfile::where('id', $request->user()->id)->first();

            if (
                (!$request->user()->tokenCan('company') && $employer_profile->company_id != $request_profile->company_id)
                ||
                ($request->user()->tokenCan('company') && $employer_profile->company_id != $request->user()->id)
            ) {
                return $this->respondForbidden('Bạn không có quyền xử lí đơn này');
            }

            $application->status = "Đã duyệt";
            $application->save();

            return $this->respondWithData(
                [
                    'application' => $application,
                ]);
        }
        catch (Exception $exception) {
            return $this->respondInternalServerError($exception->getMessage());
        }
    }

    /**
     *  @OA\Put(
     *      path="/api/applications/reject/{id}",
     *      summary="Reject application",
     *      tags={"Applications"},
     *      security={{"sanctum":{}}},
     *      @OA\Parameter(
     *          name="id",
     *          in="path",
     *          description="Application id",
     *          required=true
     *      ),
     *      @OA\Parameter(
     *          name="Accept",
     *          in="header",
     *          description="application/json",
     *          required=false
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Successfully updated application",
     *          @OA\JsonContent(
     *              example = {
                        "error": false,
                        "message": "Xử lí thành công",
                        "data": {
                            "application": {
                                "id": 1,
                                "job_id": 1,
                                "user_id": 2,
                                "cv_id": 2,
                                "status": "Đã từ chối",
                                "deleted_at": null
                            }
                        },
                        "status_code": 200
                    }
     *          ),
     *      ),
     *      @OA\Response(
     *          response=404,
     *          description="No applications found",
     *          ref="#/components/responses/NotFound"
     *      ),
     *  )
     */
    public function rejectApplication(Request $request, string $id): JsonResponse
    {
        try {
            $application = Application::where('id', $id)->first();

            if (!$application) {
                return $this->respondNotFound();
            }

            $job = Job::where('id', $application->job_id)->first();
            $employer_profile = EmployerProfile::where('id', $job->employer_id)->first();
            $request_profile = EmployerProfile::where('id', $request->user()->id)->first();

            if (
                (!$request->user()->tokenCan('company') && $employer_profile->company_id != $request_profile->company_id)
                ||
                ($request->user()->tokenCan('company') && $employer_profile->company_id != $request->user()->id)
            ) {
                return $this->respondForbidden('Bạn không có quyền xử lí đơn này');
            }

            $application->status = "Đã từ chối";
            $application->save();

            return $this->respondWithData(
                [
                    'application' => $application,
                ]);
        }
        catch (Exception $exception) {
            return $this->respondInternalServerError($exception->getMessage());
        }
    }

    /**
     *  @OA\Delete(
     *      path="/api/applications/{id}",
     *      summary="Delete application",
     *      tags={"Applications"},
     *      security={{"sanctum":{}}},
     *      @OA\Parameter(
     *          name="id",
     *          in="path",
     *          description="Application id",
     *          required=true
     *      ),
     *      @OA\Parameter(
     *          name="Accept",
     *          in="header",
     *          description="application/json",
     *          required=false
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Successfully deleted application",
     *          @OA\JsonContent(
     *              example = {
                        "error": false,
                        "message": "Xoá thành công",
                        "data": {
                            "application": {
                            "id": 1,
                            "job_id": 1,
                            "user_id": 2,
                            "cv_id": 2,
                            "status": "Đang chờ",
                            "deleted_at": "2023-05-19T13:01:57.000000Z"
                            }
                        },
                        "status_code": 200
                    }
     *          ),
     *      ),
     *      @OA\Response(
     *          response=404,
     *          description="No applications found",
     *          ref="#/components/responses/NotFound"
     *      ),
     *  )
     */
    public function deleteApplication(Request $request, string $id): JsonResponse
    {
        try {
            $application = Application::where('id', $id)->first();

            if (!$application) {
                return $this->respondNotFound();
            }

            if (!$request->user()->tokenCan('mod') && $application->user_id !== $request->user()->id) {
                return $this->respondForbidden('Bạn không có quyền xóa thông tin này');
            }

            $application->delete();

            return $this->respondWithData(
                [
                    'application' => $application,
                ], 'Xóa thành công');
        }
        catch (Exception $exception) {
            return $this->respondInternalServerError($exception->getMessage());
        }
    }
}
