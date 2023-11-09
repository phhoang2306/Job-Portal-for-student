<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateUserExperienceRequest;
use App\Models\UserExperience;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class UserExperienceController extends ApiController
{
    /**
     *  @OA\Get(
     *      path="/api/user-experiences",
     *      summary="Get all user experiences",
     *      tags={"User Experiences"},
     *      security={{"bearerAuth":{}}},
     *      @OA\Parameter(
     *          name="count_per_page",
     *          in="query",
     *          description="Number of user experiences per page",
     *          required=false
     *      ),
     *      @OA\Parameter(
     *          name="Accept",
     *          in="header",
     *          description="Accept header",
     *          required=false,
     *      ),
     *      @OA\Parameter(
     *          name="Authorization",
     *          in="header",
     *          description="Bearer {token}",
     *          required=true,
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Get all user experiences successfully",
     *          @OA\JsonContent(
     *              example=
    {
    "error": false,
    "message": "Xử lí thành công",
    "data": {
    "user_experiences": {
    "current_page": 1,
    "data": {
    {
    "id": 1,
    "user_id": 3,
    "description": "Công việc chính:\n- Viết danh sách các câu hỏi và báo giá dự án\ncho khách hàng.\n- Thực hiện phân tích, mô tả và thiết kế sơ đồ\nhệ thống.\n- Tìm hiểu về phân tích và thiết kế hệ thống.\n- Hỗ trợ rà soát và cải tiến hệ thống.\nNhững điều đạt được:\n- Học kỹ năng giao tiếp và giải quyết vấn đề.\n- Nâng cao hiệu quả làm việc nhóm.\n- Biết sử dụng thêm các công cụ thiết kế giao\ndiện.\n- Học cách phân tích hệ thống.",
    "start": "2022-09-01",
    "end": "2022-12-01"
    }
    },
    "first_page_url": "http://localhost:8000/api/user-experiences?page=1",
    "from": 1,
    "last_page": 56,
    "last_page_url": "http://localhost:8000/api/user-experiences?page=56",
    "links": {
    {
    "url": null,
    "label": "&laquo; Previous",
    "active": false
    },
    {
    "url": "http://localhost:8000/api/user-experiences?page=1",
    "label": "1",
    "active": true
    },
    {
    "url": "http://localhost:8000/api/user-experiences?page=2",
    "label": "2",
    "active": false
    },
    {
    "url": "http://localhost:8000/api/user-experiences?page=3",
    "label": "3",
    "active": false
    },
    {
    "url": "http://localhost:8000/api/user-experiences?page=4",
    "label": "4",
    "active": false
    },
    {
    "url": "http://localhost:8000/api/user-experiences?page=5",
    "label": "5",
    "active": false
    },
    {
    "url": "http://localhost:8000/api/user-experiences?page=6",
    "label": "6",
    "active": false
    },
    {
    "url": "http://localhost:8000/api/user-experiences?page=7",
    "label": "7",
    "active": false
    },
    {
    "url": "http://localhost:8000/api/user-experiences?page=8",
    "label": "8",
    "active": false
    },
    {
    "url": "http://localhost:8000/api/user-experiences?page=9",
    "label": "9",
    "active": false
    },
    {
    "url": "http://localhost:8000/api/user-experiences?page=10",
    "label": "10",
    "active": false
    },
    {
    "url": null,
    "label": "...",
    "active": false
    },
    {
    "url": "http://localhost:8000/api/user-experiences?page=55",
    "label": "55",
    "active": false
    },
    {
    "url": "http://localhost:8000/api/user-experiences?page=56",
    "label": "56",
    "active": false
    },
    {
    "url": "http://localhost:8000/api/user-experiences?page=2",
    "label": "Next &raquo;",
    "active": false
    }
    },
    "next_page_url": "http://localhost:8000/api/user-experiences?page=2",
    "path": "http://localhost:8000/api/user-experiences",
    "per_page": 1,
    "prev_page_url": null,
    "to": 1,
    "total": 56
    }
    },
    "status_code": 200
    }
     *          )
     *      ),
     *      @OA\Response(
     *          response=404,
     *          description="No user experience found",
     *          ref="#/components/responses/NotFound"
     *      )
     *  )
     */
    public function getAllUserExperiences(Request $request): JsonResponse
    {
        try {
            $count_per_page = $request->count_per_page ?? 10;
            $order_by = $request->order_by ?? 'id';
            $order_type = $request->order_type ?? 'asc';

            $user_experiences = UserExperience::orderBy($order_by, $order_type)->paginate($count_per_page);

            if (count($user_experiences) === 0) {
                return $this->respondNotFound();
            }

            return $this->respondWithData(
                [
                    'user_experiences' => $user_experiences,
                ]);
        }
        catch (Exception $exception) {
            return $this->respondInternalServerError($exception->getMessage());
        }
    }

    /**
     * @OA\Get(
     *      path="/user-experiences/user/{user_id}",
     *      operationId="getUserExperiencesByUserId",
     *      tags={"User Experiences"},
     *      summary="Get user experiences by user id",
     *      @OA\Parameter(
     *          name="user_id",
     *          description="User id",
     *          required=true,
     *          in="path"
     *      ),
     *      @OA\Parameter(
     *          name="count_per_page",
     *          description="Count per page",
     *          required=false,
     *          in="query"
     *      ),
     *      @OA\Parameter(
     *          name="Accept",
     *          in="header",
     *          description="Accept header",
     *          required=false,
     *      ),
     *      @OA\Parameter(
     *          name="Authorization",
     *          in="header",
     *          description="Bearer {token}",
     *          required=true,
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="User experiences retrieved successfully",
     *          @OA\JsonContent(
     *              example=
    {
    "error": false,
    "message": "Xử lí thành công",
    "data": {
    "user_experiences": {
    "current_page": 1,
    "data": {
    {
    "id": 7,
    "user_id": 1,
    "description": "BUSINESS ANALYST, TESTER tại TECHPLUS SOLUTION\nTừ Tháng Hai – Tháng Chín 2020\nHệ thống ngân hàng lõi\no Xem xét, phân tích và tư vấn về các thông số kỹ thuật và yêu cầu\no Thực hiện kiểm tra thủ công cho các ứng dụng\no Ghi nhật ký lỗi và theo dõi để đóng cửa, làm việc với sự phát triển",
    "start": "2021-03-01",
    "end": "2021-03-01"
    }
    },
    "first_page_url": "http://localhost:8000/api/user-experiences/user/1?page=1",
    "from": 1,
    "last_page": 1,
    "last_page_url": "http://localhost:8000/api/user-experiences/user/1?page=1",
    "links": {
    {
    "url": null,
    "label": "&laquo; Previous",
    "active": false
    },
    {
    "url": "http://localhost:8000/api/user-experiences/user/1?page=1",
    "label": "1",
    "active": true
    },
    {
    "url": null,
    "label": "Next &raquo;",
    "active": false
    }
    },
    "next_page_url": null,
    "path": "http://localhost:8000/api/user-experiences/user/1",
    "per_page": 1,
    "prev_page_url": null,
    "to": 1,
    "total": 1
    }
    },
    "status_code": 200
    }
     *          )
     *      ),
     *      @OA\Response(
     *          response=404,
     *          description="No user experience found",
     *          ref="#/components/responses/NotFound"
     *      )
     *  )
     */
    public function getUserExperiencesByUserId(Request $request, string $user_id): JsonResponse
    {
        try {
            $count_per_page = $request->count_per_page;
            $order_by = $request->order_by ?? 'id';
            $order_type = $request->order_type ?? 'asc';

            $user_experiences = UserExperience::where('user_id', $user_id)
                ->orderBy($order_by, $order_type)
                ->paginate($count_per_page);

            if (count($user_experiences) === 0) {
                return $this->respondNotFound();
            }

            return $this->respondWithData(
                [
                    'user_experiences' => $user_experiences,
                ]);
        }
        catch (Exception $exception) {
            return $this->respondInternalServerError($exception->getMessage());
        }
    }

    /**
     * @OA\Get(
     *      path="/user-experiences/{id}",
     *      tags={"User Experiences"},
     *      summary="Get user experience information",
     *      @OA\Parameter(
     *          name="id",
     *          description="User experience id",
     *          required=true,
     *          in="path"
     *      ),
     *      @OA\Parameter(
     *          name="Accept",
     *          in="header",
     *          description="Accept header",
     *          required=false,
     *      ),
     *      @OA\Parameter(
     *          name="Authorization",
     *          in="header",
     *          description="Bearer {token}",
     *          required=true,
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="User experience retrieved successfully",
     *          @OA\JsonContent(
     *              example=
    {
    "error": false,
    "message": "Xử lí thành công",
    "data": {
    "user_experience": {
    "current_page": 1,
    "data": {
    {
    "id": 1,
    "user_id": 3,
    "description": "Công việc chính:\n- Viết danh sách các câu hỏi và báo giá dự án\ncho khách hàng.\n- Thực hiện phân tích, mô tả và thiết kế sơ đồ\nhệ thống.\n- Tìm hiểu về phân tích và thiết kế hệ thống.\n- Hỗ trợ rà soát và cải tiến hệ thống.\nNhững điều đạt được:\n- Học kỹ năng giao tiếp và giải quyết vấn đề.\n- Nâng cao hiệu quả làm việc nhóm.\n- Biết sử dụng thêm các công cụ thiết kế giao\ndiện.\n- Học cách phân tích hệ thống.",
    "start": "2022-09-01",
    "end": "2022-12-01"
    }
    },
    "first_page_url": "http://localhost:8000/api/user-experiences/1?page=1",
    "from": 1,
    "last_page": 1,
    "last_page_url": "http://localhost:8000/api/user-experiences/1?page=1",
    "links": {
    {
    "url": null,
    "label": "&laquo; Previous",
    "active": false
    },
    {
    "url": "http://localhost:8000/api/user-experiences/1?page=1",
    "label": "1",
    "active": true
    },
    {
    "url": null,
    "label": "Next &raquo;",
    "active": false
    }
    },
    "next_page_url": null,
    "path": "http://localhost:8000/api/user-experiences/1",
    "per_page": 1,
    "prev_page_url": null,
    "to": 1,
    "total": 1
    }
    },
    "status_code": 200
    }
     *          )
     *      ),
     *      @OA\Response(
     *          response=404,
     *          description="No user experience found",
     *          ref="#/components/responses/NotFound"
     *      )
     *  )
     */
    public function getUserExperienceById(Request $request, string $id): JsonResponse
    {
        try {
            $user_experience = UserExperience::where('id', $id)->first();

            if (!$user_experience) {
                return $this->respondNotFound();
            }

            return $this->respondWithData(
                [
                    'user_experience' => $user_experience,
                ]);
        }
        catch (Exception $exception) {
            return $this->respondInternalServerError($exception->getMessage());
        }
    }

    /**
     * @OA\Post(
     *      path="/api/user-experiences",
     *      tags={"User Experiences"},
     *      summary="Create user experience",
     *      @OA\Parameter(
     *          name="Accept",
     *          in="header",
     *          description="Accept header",
     *          required=false,
     *      ),
     *      @OA\Parameter(
     *          name="Authorization",
     *          in="header",
     *          description="Bearer {token}",
     *          required=true,
     *      ),
     *      @OA\RequestBody(
     *          required=true,
     *          @OA\JsonContent(
     *              example=
    {
    "description": "des",
    "start": "2021-05-21",
    "end": "2022-05-21",
    }
     *          )
     *      ),
     *      @OA\Response(
     *          response=201,
     *          description="User experience created successfully",
     *          @OA\JsonContent(
     *              example=
    {
    "error": false,
    "message": "Tạo thành công",
    "data": {
    "user_experience": {
    "user_id": 2,
    "description": "des",
    "start": "2021-05-21",
    "end": "2022-05-21",
    "id": 57
    }
    },
    "status_code": 201
    }
     *          )
     *      ),
     *      @OA\Response(
     *          response=500,
     *          description="Internal server error",
     *          ref="#/components/responses/InternalServerError"
     *      )
     *  )
     */
    public function createUserExperience(CreateUserExperienceRequest $request): JsonResponse
    {
        try {
            $user_experience = new UserExperience();
            $user_experience->user_id = $request->user()->id;
            $user_experience->title = $request->title;
            $user_experience->position = $request->position;
            $user_experience->description = $request->description;
            $user_experience->start = $request->start;
            $user_experience->end = $request->end;
            $user_experience->save();

            return $this->respondCreated(
                [
                    'user_experience' => $user_experience,
                ]);
        }
        catch (Exception $exception) {
            return $this->respondInternalServerError($exception->getMessage());
        }
    }

    /**
     * @OA\Put(
     *      path="/api/user-experiences/{id}",
     *      tags={"User Experiences"},
     *      summary="Update user experience",
     *      @OA\Parameter(
     *          name="id",
     *          description="User experience id",
     *          required=true,
     *          in="path",
     *      ),
     *      @OA\Parameter(
     *          name="Accept",
     *          in="header",
     *          description="Accept header",
     *          required=false,
     *      ),
     *      @OA\Parameter(
     *          name="Authorization",
     *          in="header",
     *          description="Bearer {token}",
     *          required=true,
     *      ),
     *      @OA\RequestBody(
     *          required=true,
     *          @OA\JsonContent(
     *              example=
    {
    "description": "desdes"
    }
     *          )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="User experience updated successfully",
     *          @OA\JsonContent(
     *              example=
    {
    "error": false,
    "message": "Xử lí thành công",
    "data": {
    "user_experience": {
    "id": 57,
    "user_id": 2,
    "description": "desdes",
    "start": "2021-05-21",
    "end": "2022-05-21"
    }
    },
    "status_code": 200
    }
     *          )
     *      ),
     *      @OA\Response(
     *          response=404,
     *          description="No user experience found",
     *          ref="#/components/responses/NotFound"
     *      )
     *  )
     */
    public function updateUserExperience(Request $request, string $id): JsonResponse
    {
        try {
            $user_experience = UserExperience::where('id', $id)->first();

            if (!$user_experience) {
                return $this->respondNotFound();
            }

            if ($user_experience->user_id !== $request->user()->id) {
                return $this->respondUnauthorized('Bạn không có quyền chỉnh sửa thông tin này');
            }

            $user_experience->title = $request->title ?? $user_experience->title;
            $user_experience->position = $request->position ?? $user_experience->position;
            $user_experience->description = $request->description ?? $user_experience->description;
            $user_experience->start = $request->start ?? $user_experience->start;
            $user_experience->end = $request->end ?? $user_experience->end;
            $user_experience->save();

            return $this->respondWithData(
                [
                    'user_experience' => $user_experience,
                ]);
        }
        catch (Exception $exception) {
            return $this->respondInternalServerError($exception->getMessage());
        }
    }

    /**
     * @OA\Delete(
     *      path="/api/user-experiences/{id}",
     *      tags={"User Experiences"},
     *      summary="Delete user experience",
     *      @OA\Parameter(
     *          name="id",
     *          description="User experience id",
     *          required=true,
     *          in="path",
     *      ),
     *      @OA\Parameter(
     *          name="Accept",
     *          in="header",
     *          description="Accept header",
     *          required=false,
     *      ),
     *      @OA\Parameter(
     *          name="Authorization",
     *          in="header",
     *          description="Bearer {token}",
     *          required=true,
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="User experience deleted successfully",
     *          @OA\JsonContent(
     *              example=
    {
    "error": false,
    "message": "Xóa thành công",
    "data": {
    "user_experience": {
    "id": 57,
    "user_id": 2,
    "description": "desdes",
    "start": "2021-05-21",
    "end": "2022-05-21"
    }
    },
    "status_code": 200
    }
     *          )
     *      ),
     *      @OA\Response(
     *          response=404,
     *          description="No user experience found",
     *          ref="#/components/responses/NotFound"
     *      )
     *  )
     */
    public function deleteUserExperience(Request $request, string $id): JsonResponse
    {
        try {
            $user_experience = UserExperience::where('id', $id)->first();

            if (!$user_experience) {
                return $this->respondNotFound();
            }

            if (!$request->user()->tokenCan('mod') && $user_experience->user_id !== $request->user()->id) {
                return $this->respondUnauthorized('Bạn không có quyền xóa thông tin này');
            }

            $user_experience->delete();

            return $this->respondWithData(
                [
                    'user_experience' => $user_experience,
                ], 'Xóa thành công');
        }
        catch (Exception $exception) {
            return $this->respondInternalServerError($exception->getMessage());
        }
    }
}
