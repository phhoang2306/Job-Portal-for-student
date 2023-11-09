<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateUserSkillRequest;
use App\Models\UserSkill;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class UserSkillController extends ApiController
{
    /**
     *  @OA\Get(
     *      path="/api/user-skills",
     *      summary="Get all user skills",
     *      tags={"User Skills"},
     *      security={{"bearerAuth":{}}},
     *      @OA\Parameter(
     *          name="count_per_page",
     *          in="query",
     *          description="Number of user achievements per page",
     *          required=false,
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
     *          description="Successfully retrieved user skills",
     *          @OA\JsonContent(
     *              example=
    {
    "error": false,
    "message": "Xử lí thành công",
    "data": {
    "user_skills": {
    "current_page": 1,
    "data": {
    {
    "id": 1,
    "user_id": 1,
    "skill": "Quản lý đa nhiệm vụ"
    }
    },
    "first_page_url": "http://localhost:8000/api/user-skills?page=1",
    "from": 1,
    "last_page": 461,
    "last_page_url": "http://localhost:8000/api/user-skills?page=461",
    "links": {
    {
    "url": null,
    "label": "&laquo; Previous",
    "active": false
    },
    {
    "url": "http://localhost:8000/api/user-skills?page=1",
    "label": "1",
    "active": true
    },
    {
    "url": "http://localhost:8000/api/user-skills?page=2",
    "label": "2",
    "active": false
    },
    {
    "url": "http://localhost:8000/api/user-skills?page=3",
    "label": "3",
    "active": false
    },
    {
    "url": "http://localhost:8000/api/user-skills?page=4",
    "label": "4",
    "active": false
    },
    {
    "url": "http://localhost:8000/api/user-skills?page=5",
    "label": "5",
    "active": false
    },
    {
    "url": "http://localhost:8000/api/user-skills?page=6",
    "label": "6",
    "active": false
    },
    {
    "url": "http://localhost:8000/api/user-skills?page=7",
    "label": "7",
    "active": false
    },
    {
    "url": "http://localhost:8000/api/user-skills?page=8",
    "label": "8",
    "active": false
    },
    {
    "url": "http://localhost:8000/api/user-skills?page=9",
    "label": "9",
    "active": false
    },
    {
    "url": "http://localhost:8000/api/user-skills?page=10",
    "label": "10",
    "active": false
    },
    {
    "url": null,
    "label": "...",
    "active": false
    },
    {
    "url": "http://localhost:8000/api/user-skills?page=460",
    "label": "460",
    "active": false
    },
    {
    "url": "http://localhost:8000/api/user-skills?page=461",
    "label": "461",
    "active": false
    },
    {
    "url": "http://localhost:8000/api/user-skills?page=2",
    "label": "Next &raquo;",
    "active": false
    }
    },
    "next_page_url": "http://localhost:8000/api/user-skills?page=2",
    "path": "http://localhost:8000/api/user-skills",
    "per_page": 1,
    "prev_page_url": null,
    "to": 1,
    "total": 461
    }
    },
    "status_code": 200
    }
     *          )
     *      ),
     *      @OA\Response(
     *          response=404,
     *          description="No user skills found",
     *          ref="#/components/responses/NotFound"
     *      )
     *  )
     */
    public function getAllUserSkills(Request $request): JsonResponse
    {
        try {
            $count_per_page = $request->count_per_page ?? 10;
            $order_by = $request->order_by ?? 'id';
            $order_type = $request->order_type ?? 'asc';

            $user_skills = UserSkill::orderBy($order_by, $order_type)->paginate($count_per_page);

            if (count($user_skills) === 0) {
                return $this->respondNotFound();
            }

            return $this->respondWithData(
                [
                    'user_skills' => $user_skills,
                ]);
        }
        catch (Exception $exception) {
            return $this->respondInternalServerError($exception->getMessage());
        }
    }

    /**
     *  @OA\Get(
     *      path="/user-skills/user/{user_id}",
     *      tags={"User Skills"},
     *      summary="Get user skills by user id",
     *      security={{"bearerAuth":{}}},
     *      @OA\Parameter(
     *          name="count_per_page",
     *          in="query",
     *          description="Number of user achievements per page",
     *          required=false,
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
     *          description="User skills retrieved successfully",
     *          @OA\JsonContent(
     *              example=
    {
    "error": false,
    "message": "Successfully retrieved user skills",
    "data": {
    "user_skills": {
    "current_page": 1,
    "data": {
    {
    "id": 1,
    "user_id": 1,
    "skill": "Quản lý đa nhiệm vụ"
    }
    },
    "first_page_url": "http://localhost:8000/api/user-skills/user/1?page=1",
    "from": 1,
    "last_page": 3,
    "last_page_url": "http://localhost:8000/api/user-skills/user/1?page=3",
    "links": {
    {
    "url": null,
    "label": "&laquo; Previous",
    "active": false
    },
    {
    "url": "http://localhost:8000/api/user-skills/user/1?page=1",
    "label": "1",
    "active": true
    },
    {
    "url": "http://localhost:8000/api/user-skills/user/1?page=2",
    "label": "2",
    "active": false
    },
    {
    "url": "http://localhost:8000/api/user-skills/user/1?page=3",
    "label": "3",
    "active": false
    },
    {
    "url": "http://localhost:8000/api/user-skills/user/1?page=2",
    "label": "Next &raquo;",
    "active": false
    }
    },
    "next_page_url": "http://localhost:8000/api/user-skills/user/1?page=2",
    "path": "http://localhost:8000/api/user-skills/user/1",
    "per_page": 1,
    "prev_page_url": null,
    "to": 1,
    "total": 3
    }
    },
    "status_code": 200
    }
     *          )
     *      ),
     *      @OA\Response(
     *          response=404,
     *          description="No user skills found",
     *          ref="#/components/responses/NotFound"
     *      )
     *  )
     */
    public function getUserSkillsByUserId(Request $request, string $user_id): JsonResponse
    {
        try {
            $count_per_page = $request->count_per_page ?? 10;
            $order_by = $request->order_by ?? 'id';
            $order_type = $request->order_type ?? 'asc';

            $user_skills = UserSkill::where('user_id', $user_id)
                ->orderBy($order_by, $order_type)
                ->paginate($count_per_page);

            if (count($user_skills) === 0) {
                return $this->respondNotFound('No user skills found');
            }

            return $this->respondWithData(
                [
                    'user_skills' => $user_skills,
                ]
                , 'Successfully retrieved user skills');
        }
        catch (Exception $exception) {
            return $this->respondInternalServerError($exception->getMessage());
        }
    }

    /**
     *  @OA\Get(
     *      path="/user-skills/{id}",
     *      tags={"User Skills"},
     *      summary="Get user skill by id",
     *      security={{"bearerAuth":{}}},
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
     *          description="User skill retrieved successfully",
     *          @OA\JsonContent(
     *              example=
    {
    "error": false,
    "message": "Xử lí thành công",
    "data": {
    "user_skill": {
    "id": 1,
    "user_id": 1,
    "skill": "Quản lý đa nhiệm vụ"
    }
    },
    "status_code": 200
    }
     *          )
     *      ),
     *      @OA\Response(
     *          response=404,
     *          description="User skill not found",
     *          ref="#/components/responses/NotFound"
     *      )
     *  )
     */
    public function getUserSkillById(Request $request, string $id): JsonResponse
    {
        try {
            $user_skill = UserSkill::where('id', $id)->first();

            if (!$user_skill) {
                return $this->respondNotFound();
            }

            return $this->respondWithData(
                [
                    'user_skill' => $user_skill,
                ]);
        }
        catch (Exception $exception) {
            return $this->respondInternalServerError($exception->getMessage());
        }
    }

    /**
     *  @OA\Post(
     *      path="/user-skills",
     *      tags={"User Skills"},
     *      summary="Create user skill",
     *      security={{"bearerAuth":{}}},
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
    "skill": "skill1",
    }
     *          ),
     *      ),
     *      @OA\Response(
     *          response=201,
     *          description="User skill created successfully",
     *          @OA\JsonContent(
     *              example=
    {
    "error": false,
    "message": "Tạo thành công",
    "data": {
    "user_skill": {
    "user_id": 2,
    "skill": "skill1",
    "id": 462
    }
    },
    "status_code": 201
    }
     *          )
     *      ),
     *      @OA\Response(
     *          response=404,
     *          description="User skill not found",
     *          ref="#/components/responses/NotFound"
     *      )
     *  )
     */
    public function createUserSkill(CreateUserSkillRequest $request): JsonResponse
    {
        try {
            $user_skill = new UserSkill();

            $user_skill->user_id = $request->user()->id;
            $user_skill->skill = $request->skill;
            $user_skill->save();

            return $this->respondCreated(
                [
                    'user_skill' => $user_skill,
                ]);
        }
        catch (Exception $exception) {
            return $this->respondInternalServerError($exception->getMessage());
        }
    }

    /**
     *  @OA\Put(
     *      path="/user-skills/{id}",
     *      tags={"User Skills"},
     *      summary="Update user skill by id",
     *      security={{"bearerAuth":{}}},
     *      @OA\Parameter(
     *          name="id",
     *          in="path",
     *          description="User skill id",
     *          required=true,
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
    "skill": "skill2",
    }
     *          )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="User skill updated successfully",
     *          @OA\JsonContent(
     *              example=
    {
    "error": false,
    "message": "Xử lí thành công",
    "data": {
    "user_skill": {
    "id": 462,
    "user_id": 2,
    "skill": "skill2"
    }
    },
    "status_code": 200
    }
     *          )
     *      ),
     *      @OA\Response(
     *          response=404,
     *          description="User skill not found",
     *          ref="#/components/responses/NotFound"
     *      )
     *  )
     */
    public function updateUserSkill(Request $request, string $id): JsonResponse
    {
        try {
            $user_skill = UserSkill::where('id', $id)->first();

            if (!$user_skill) {
                return $this->respondNotFound();
            }

            if ($user_skill->user_id !== $request->user()->id) {
                return $this->respondUnauthorized('Bạn không có quyền chỉnh sửa thông tin này');
            }

            $user_skill->skill = $request->skill ?? $user_skill->skill;
            $user_skill->save();

            return $this->respondWithData(
                [
                    'user_skill' => $user_skill,
                ]);
        }
        catch (Exception $exception) {
            return $this->respondInternalServerError($exception->getMessage());
        }
    }

    /**
     *  @OA\Delete(
     *      path="/user-skills/{id}",
     *      tags={"User Skills"},
     *      summary="Delete user skill by id",
     *      security={{"bearerAuth":{}}},
     *      @OA\Parameter(
     *          name="id",
     *          in="path",
     *          description="User skill id",
     *          required=true,
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
     *          description="User skill deleted successfully",
     *          @OA\JsonContent(
     *              example=
    {
    "error": false,
    "message": "Xoá thành công",
    "data": {
    "user_skill": {
    "id": 462,
    "user_id": 2,
    "skill": "skill2"
    }
    },
    "status_code": 200
    }
     *          )
     *      ),
     *      @OA\Response(
     *          response=404,
     *          description="User skill not found",
     *          ref="#/components/responses/NotFound"
     *      )
     *  )
     */
    public function deleteUserSkill(Request $request, string $id): JsonResponse
    {
        try {
            $user_skill = UserSkill::where('id', $id)->first();

            if (!$user_skill) {
                return $this->respondNotFound();
            }

            if (!$request->user()->tokenCan('mod') && $user_skill->user_id !== $request->user()->id) {
                return $this->respondUnauthorized('Bạn không có quyền xoá thông tin này');
            }

            $user_skill->delete();

            return $this->respondWithData(
                [
                    'user_skill' => $user_skill,
                ], 'Xoá thành công');
        }
        catch (Exception $exception) {
            return $this->respondInternalServerError($exception->getMessage());
        }
    }
}
