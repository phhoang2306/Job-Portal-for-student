<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateUserEducationRequest;
use App\Models\UserEducation;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class UserEducationController extends ApiController
{
    /**
     *  @OA\Get(
     *      path="/api/user-educations",
     *      summary="Get all user educations",
     *      tags={"User Education"},
     *      security={{"bearerAuth":{}}},
     *      @OA\Parameter(
     *          name="count_per_page",
     *          in="query",
     *          description="Number of user educations per page",
     *          required=false
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="User educations",
     *          @OA\JsonContent(
     *               example=
    {
    "error": false,
    "message": "Xử lí thành công",
    "data": {
    "user_educations": {
    "current_page": 1,
    "data": {
    {
    "id": 1,
    "user_id": 1,
    "university": "Đại học Ngoại thương",
    "start": "2016-01-01",
    "end": "2020-01-01"
    }
    },
    "first_page_url": "http://localhost:8000/api/user-educations?page=1",
    "from": 1,
    "last_page": 54,
    "last_page_url": "http://localhost:8000/api/user-educations?page=54",
    "links": {
    {
    "url": null,
    "label": "&laquo; Previous",
    "active": false
    },
    {
    "url": "http://localhost:8000/api/user-educations?page=1",
    "label": "1",
    "active": true
    },
    {
    "url": "http://localhost:8000/api/user-educations?page=2",
    "label": "2",
    "active": false
    },
    {
    "url": "http://localhost:8000/api/user-educations?page=3",
    "label": "3",
    "active": false
    },
    {
    "url": "http://localhost:8000/api/user-educations?page=4",
    "label": "4",
    "active": false
    },
    {
    "url": "http://localhost:8000/api/user-educations?page=5",
    "label": "5",
    "active": false
    },
    {
    "url": "http://localhost:8000/api/user-educations?page=6",
    "label": "6",
    "active": false
    },
    {
    "url": "http://localhost:8000/api/user-educations?page=7",
    "label": "7",
    "active": false
    },
    {
    "url": "http://localhost:8000/api/user-educations?page=8",
    "label": "8",
    "active": false
    },
    {
    "url": "http://localhost:8000/api/user-educations?page=9",
    "label": "9",
    "active": false
    },
    {
    "url": "http://localhost:8000/api/user-educations?page=10",
    "label": "10",
    "active": false
    },
    {
    "url": null,
    "label": "...",
    "active": false
    },
    {
    "url": "http://localhost:8000/api/user-educations?page=53",
    "label": "53",
    "active": false
    },
    {
    "url": "http://localhost:8000/api/user-educations?page=54",
    "label": "54",
    "active": false
    },
    {
    "url": "http://localhost:8000/api/user-educations?page=2",
    "label": "Next &raquo;",
    "active": false
    }
    },
    "next_page_url": "http://localhost:8000/api/user-educations?page=2",
    "path": "http://localhost:8000/api/user-educations",
    "per_page": 1,
    "prev_page_url": null,
    "to": 1,
    "total": 54
    }
    },
    "status_code": 200
    }
     *          )
     *      ),
     *      @OA\Response(
     *          response=404,
     *          description="User educations not found",
     *          ref="#/components/responses/NotFound"
     *      )
     *  )
     */
    public function getAllUserEducations(Request $request): JsonResponse
    {
        try {
            $count_per_page = $request->count_per_page ?? 10;
            $order_by = $request->order_by ?? 'id';
            $order_type = $request->order_type ?? 'asc';

            $user_educations = UserEducation::orderBy($order_by, $order_type)->paginate($count_per_page);

            if (count($user_educations) === 0) {
                return $this->respondNotFound();
            }

            return $this->respondWithData(
                [
                    'user_educations' => $user_educations,
                ]);
        }
        catch (Exception $exception) {
            return $this->respondInternalServerError($exception->getMessage());
        }
    }

    /**
     *  @OA\Get(
     *      path="/api/user-educations/users/{user_id}",
     *      summary="Get user educations by user id",
     *      tags={"User Education"},
     *      security={{"bearerAuth":{}}},
     *      @OA\Parameter(
     *          name="user_id",
     *          in="path",
     *          description="User id",
     *          required=true
     *      ),
     *      @OA\Parameter(
     *          name="count_per_page",
     *          in="query",
     *          description="Number of user educations per page",
     *          required=false
     *      ),
     *      @OA\Parameter(
     *          name="Accept",
     *          in="header",
     *          description="Accept",
     *          required=true
     *      ),
     *      @OA\Parameter(
     *          name="Authorization",
     *          in="header",
     *          description="Bearer {token}",
     *          required=true
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="User educations",
     *          @OA\JsonContent(
     *               example=
    {
    "error": false,
    "message": "Xử lí thành công",
    "data": {
    "user_educations": {
    "current_page": 1,
    "data": {
    {
    "id": 1,
    "user_id": 1,
    "university": "Đại học Ngoại thương",
    "start": "2016-01-01",
    "end": "2020-01-01"
    }
    },
    "first_page_url": "http://localhost:8000/api/user-educations/user/1?page=1",
    "from": 1,
    "last_page": 1,
    "last_page_url": "http://localhost:8000/api/user-educations/user/1?page=1",
    "links": {
    {
    "url": null,
    "label": "&laquo; Previous",
    "active": false
    },
    {
    "url": "http://localhost:8000/api/user-educations/user/1?page=1",
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
    "path": "http://localhost:8000/api/user-educations/user/1",
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
     *          description="User educations not found",
     *          ref="#/components/responses/NotFound"
     *      )
     *  )
     */
    public function getUserEducationsByUserId(Request $request, string $user_id): JsonResponse
    {
        try {
            $count_per_page = $request->count_per_page;
            $order_by = $request->order_by ?? 'id';
            $order_type = $request->order_type ?? 'asc';

            $user_educations = UserEducation::where('user_id', $user_id)
                ->orderBy($order_by, $order_type)
                ->paginate($count_per_page);

            if (count($user_educations) === 0) {
                return $this->respondNotFound();
            }

            return $this->respondWithData(
                [
                    'user_educations' => $user_educations,
                ]);
        }
        catch (Exception $exception) {
            return $this->respondInternalServerError($exception->getMessage());
        }
    }

    /**
     *  @OA\Get(
     *      path="/api/user-educations/{id}",
     *      summary="Get user education by id",
     *      tags={"User Education"},
     *      security={{"bearerAuth":{}}},
     *      @OA\Parameter(
     *          name="id",
     *          in="path",
     *          description="User education id",
     *          required=true
     *      ),
     *      @OA\Parameter(
     *          name="Accept",
     *          in="header",
     *          description="Accept",
     *          required=true
     *      ),
     *      @OA\Parameter(
     *          name="Authorization",
     *          in="header",
     *          description="Bearer {token}",
     *          required=true
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="User education",
     *          @OA\JsonContent(
     *               example=
    {
    "error": false,
    "message": "Xử lí thành công",
    "data": {
    "user_education": {
    "current_page": 1,
    "data": {
    {
    "id": 1,
    "user_id": 1,
    "university": "Đại học Ngoại thương",
    "start": "2016-01-01",
    "end": "2020-01-01"
    }
    },
    "first_page_url": "http://localhost:8000/api/user-educations/1?page=1",
    "from": 1,
    "last_page": 1,
    "last_page_url": "http://localhost:8000/api/user-educations/1?page=1",
    "links": {
    {
    "url": null,
    "label": "&laquo; Previous",
    "active": false
    },
    {
    "url": "http://localhost:8000/api/user-educations/1?page=1",
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
    "path": "http://localhost:8000/api/user-educations/1",
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
     *          description="User education not found",
     *          ref="#/components/responses/NotFound"
     *      )
     *  )
     */
    public function getUserEducationById(Request $request, string $id): JsonResponse
    {
        try {
            $user_education = UserEducation::where('id', $id)->first();

            if (!$user_education) {
                return $this->respondNotFound();
            }

            return $this->respondWithData(
                [
                    'user_education' => $user_education,
                ]);
        }
        catch (Exception $exception) {
            return $this->respondInternalServerError($exception->getMessage());
        }
    }

    /**
     *  @OA\Post(
     *      path="/api/user-educations",
     *      tags={"User Education"},
     *      summary="Create user education",
     *      security={{"bearerAuth":{}}},
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
    "university": "uni",
    "start": "2021-05-20",
    "end": "2022-05-20"
    }
     *          )
     *      ),
     *      @OA\Response(
     *          response=201,
     *          description="User education created",
     *          @OA\JsonContent(
     *              example=
    {
    "error": false,
    "message": "Tạo thành công",
    "data": {
    "user_education": {
    "user_id": 1,
    "university": "uni",
    "start": "2021-05-20",
    "end": "2022-05-20",
    "id": 55
    }
    },
    "status_code": 201
    }
     *          )
     *      ),
     *      @OA\Response(
     *          response=400,
     *          description="Bad request",
     *          ref="#/components/responses/BadRequest"
     *      )
     *  )
     */
    public function createUserEducation(CreateUserEducationRequest $request): JsonResponse
    {
        try {
            $user_education = new UserEducation();
            $user_education->user_id = $request->user()->id;
            $user_education->university = $request->university;
            $user_education->major = $request->major;
            $user_education->start = $request->start;
            $user_education->end = $request->end;
            $user_education->save();

            return $this->respondCreated(
                [
                    'user_education' => $user_education,
                ]);
        }
        catch (Exception $exception) {
            return $this->respondInternalServerError($exception->getMessage());
        }
    }

    /**
     *  @OA\Put(
     *      path="/api/user-educations/{id}",
     *      tags={"User Education"},
     *      summary="Update user education",
     *      security={{"bearerAuth":{}}},
     *      @OA\Parameter(
     *          name="id",
     *          in="path",
     *          description="User education id",
     *          required=true
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
    "start": "2021-05-21",
    "end": "2022-05-21"
    }
     *          )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="User education updated",
     *          @OA\JsonContent(
     *              example=
    {
    "error": false,
    "message": "Xử lí thành công",
    "data": {
    "user_education": {
    "id": 55,
    "user_id": 1,
    "university": "uni",
    "start": "2021-05-21",
    "end": "2022-05-21"
    }
    },
    "status_code": 200
    }
     *          )
     *      ),
     *      @OA\Response(
     *          response=400,
     *          description="Bad request",
     *          ref="#/components/responses/BadRequest"
     *      )
     *  )
     */
    public function updateUserEducation(Request $request, string $id): JsonResponse
    {
        try {
            $user_education = UserEducation::where('id', $id)->first();

            if (!$user_education) {
                return $this->respondNotFound();
            }

            if ($user_education->user_id !== $request->user()->id) {
                return $this->respondUnauthorized('Bạn không có quyền chỉnh sửa thông tin này');
            }

            $user_education->university = $request->university ?? $user_education->university;
            $user_education->major = $request->major ?? $user_education->major;
            $user_education->start = $request->start ?? $user_education->start;
            $user_education->end = $request->end ?? $user_education->end;
            $user_education->save();

            return $this->respondWithData(
                [
                    'user_education' => $user_education,
                ]);
        }
        catch (Exception $exception) {
            return $this->respondInternalServerError($exception->getMessage());
        }
    }

    /**
     *  @OA\Delete(
     *      path="/api/user-educations/{id}",
     *      tags={"User Education"},
     *      summary="Delete user education",
     *      security={{"bearerAuth":{}}},
     *      @OA\Parameter(
     *          name="id",
     *          in="path",
     *          description="User education id",
     *          required=true
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
     *          description="User education deleted",
     *          @OA\JsonContent(
     *              example=
    {
    "error": false,
    "message": "Xoá thành công",
    "data": {
    "user_education": {
    "id": 55,
    "user_id": 1,
    "university": "uni",
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
     *          description="User education not found",
     *          ref="#/components/responses/NotFound"
     *      )
     *  )
     */
    public function deleteUserEducation(Request $request, string $id): JsonResponse
    {
        try {
            $user_education = UserEducation::where('id', $id)->first();

            if (!$user_education) {
                return $this->respondNotFound();
            }

            if (!$request->user()->tokenCan('mod') && $user_education->user_id !== $request->user()->id) {
                return $this->respondUnauthorized('Bạn không có quyền xóa thông tin này');
            }

            $user_education->delete();

            return $this->respondWithData(
                [
                    'user_education' => $user_education,
                ], 'Xoá thành công');
        }
        catch (Exception $exception) {
            return $this->respondInternalServerError($exception->getMessage());
        }
    }
}
