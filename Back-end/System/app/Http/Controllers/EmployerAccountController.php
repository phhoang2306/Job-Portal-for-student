<?php

namespace App\Http\Controllers;

use App\Http\Requests\SignUpRequest;
use App\Http\Requests\UpdatePasswordRequest;
use App\Models\EmployerAccount;
use App\Models\EmployerProfile;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class EmployerAccountController extends ApiController
{
    /**
     *  @OA\Get(
     *      path="/employer-accounts",
     *      summary="Get employer accounts",
     *      tags={"Employer Accounts"},
     *      security={{"sanctum":{}}},
     *      @OA\Parameter(
     *          name="company_id",
     *          in="query",
     *          description="Filter by company id",
     *      ),
     *      @OA\Parameter(
     *          name="order_by",
     *          in="query",
     *          description="Order by field",
     *      ),
     *      @OA\Parameter(
     *          name="order_type",
     *          in="query",
     *          description="Order type (asc/desc)",
     *      ),
     *      @OA\Parameter(
     *          name="count_per_page",
     *          in="query",
     *          description="Count per page",
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Employer accounts",
     *          @OA\JsonContent(
     *              example=
    {
    "error": false,
    "message": "Xử lí thành công",
    "data": {
    "employer_accounts": {
    "current_page": 1,
    "data": {
    {
    "id": 1,
    "username": "nva",
    "is_banned": 0,
    "locked_until": null,
    "last_login": null,
    "profile": {
    "id": 1,
    "company_id": 20,
    "full_name": "Nguyen Van A",
    "avatar": "https://i.imgur.com/hepj9ZS.png"
    }
    },
    {
    "id": 2,
    "username": "nhoang",
    "is_banned": 0,
    "locked_until": null,
    "last_login": null,
    "profile": {
    "id": 2,
    "company_id": 5,
    "full_name": "Nguyen Khanh Hoang",
    "avatar": "https://i.imgur.com/hepj9ZS.png"
    }
    }
    },
    "first_page_url": "http://127.0.0.1:8000/api/employer-accounts?page=1",
    "from": 1,
    "last_page": 20,
    "last_page_url": "http://127.0.0.1:8000/api/employer-accounts?page=20",
    "links": {
    {
    "url": null,
    "label": "&laquo; Previous",
    "active": false
    },
    {
    "url": "http://127.0.0.1:8000/api/employer-accounts?page=1",
    "label": "1",
    "active": true
    },
    {
    "url": "http://127.0.0.1:8000/api/employer-accounts?page=2",
    "label": "2",
    "active": false
    },
    {
    "url": "http://127.0.0.1:8000/api/employer-accounts?page=3",
    "label": "3",
    "active": false
    },
    {
    "url": "http://127.0.0.1:8000/api/employer-accounts?page=4",
    "label": "4",
    "active": false
    },
    {
    "url": "http://127.0.0.1:8000/api/employer-accounts?page=5",
    "label": "5",
    "active": false
    },
    {
    "url": "http://127.0.0.1:8000/api/employer-accounts?page=6",
    "label": "6",
    "active": false
    },
    {
    "url": "http://127.0.0.1:8000/api/employer-accounts?page=7",
    "label": "7",
    "active": false
    },
    {
    "url": "http://127.0.0.1:8000/api/employer-accounts?page=8",
    "label": "8",
    "active": false
    },
    {
    "url": "http://127.0.0.1:8000/api/employer-accounts?page=9",
    "label": "9",
    "active": false
    },
    {
    "url": "http://127.0.0.1:8000/api/employer-accounts?page=10",
    "label": "10",
    "active": false
    },
    {
    "url": null,
    "label": "...",
    "active": false
    },
    {
    "url": "http://127.0.0.1:8000/api/employer-accounts?page=19",
    "label": "19",
    "active": false
    },
    {
    "url": "http://127.0.0.1:8000/api/employer-accounts?page=20",
    "label": "20",
    "active": false
    },
    {
    "url": "http://127.0.0.1:8000/api/employer-accounts?page=2",
    "label": "Next &raquo;",
    "active": false
    }
    },
    "next_page_url": "http://127.0.0.1:8000/api/employer-accounts?page=2",
    "path": "http://127.0.0.1:8000/api/employer-accounts",
    "per_page": 2,
    "prev_page_url": null,
    "to": 2,
    "total": 40
    }
    },
    "status_code": 200
    }
     *          )
     *      ),
     *      @OA\Response(
     *          response=404,
     *          description="Employer accounts not found",
     *          ref="#/components/responses/NotFound"
     *      )
     *  )
     */
    public function getEmployerAccounts(Request $request): JsonResponse
    {
        try {
            $count_per_page = $request->count_per_page ?? 10;
            $order_by = $request->order_by ?? 'id';
            $order_type = $request->order_type ?? 'asc';

            $employer_accounts = EmployerAccount::filter($request, EmployerAccount::query())
                ->with('profile')
                ->orderBy($order_by, $order_type)
                ->paginate($count_per_page);

            if (count($employer_accounts) === 0) {
                return $this->respondNotFound();
            }

            return $this->respondWithData(
                [
                    'employer_accounts' => $employer_accounts,
                ]);
        }
        catch (Exception $exception) {
            return $this->respondInternalServerError($exception->getMessage());
        }
    }

    /**
     * @OA\Get(
     *      path="/employer-accounts/{id}",
     *      tags={"Employer Accounts"},
     *      summary="Get employer account information",
     *      security={{"sanctum":{}}},
     *      @OA\Parameter(
     *          name="id",
     *          description="Employer account id",
     *          required=true,
     *          in="path",
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Employer account information",
     *          @OA\JsonContent(
     *              example=
    {
    "error": false,
    "message": "Xử lí thành công",
    "data": {
    "employer_account": {
    "id": 3,
    "username": "tphu",
    "is_banned": 0,
    "locked_until": null,
    "last_login": null,
    "profile": {
    "id": 3,
    "company_id": 20,
    "full_name": "Le Trong Phu",
    "avatar": "https://i.imgur.com/hepj9ZS.png"
    }
    }
    },
    "status_code": 200
    }
     *          )
     *      ),
     *      @OA\Response(
     *          response=404,
     *          description="Employer account not found",
     *          ref="#/components/responses/NotFound"
     *      )
     *  )
     */
    public function getEmployerAccountById(Request $request, string $id): JsonResponse
    {
        try {
            $employer_account = EmployerAccount::where('id', $id)->with('profile')
                ->first();

            if (!$employer_account) {
                return $this->respondNotFound();
            }

            return $this->respondWithData(
                [
                    'employer_account' => $employer_account,
                ]);
        }
        catch (Exception $exception) {
            return $this->respondInternalServerError($exception->getMessage());
        }
    }

    /**
     * @OA\Post(
     *      path="/employer-accounts",
     *      tags={"Employer Accounts"},
     *      summary="Create employer account",
     *      security={{"sanctum":{}}},
     *      @OA\RequestBody(
     *          required=true,
     *          description="Create employer account request body",
     *          @OA\JsonContent(
     *              required={"username", "password"},
     *              @OA\Property(
     *                  property="username",
     *                  type="string",
     *                  example="tphu"
     *              ),
     *              @OA\Property(
     *                  property="password",
     *                  type="string",
     *                  example="123456"
     *              ),
     *              @OA\Property(
     *                  property="full_name",
     *                  type="string",
     *                  example="Le Trong Phu"
     *              ),
     *              @OA\Property(
     *                  property="avatar",
     *                  type="string",
     *                  example="https://i.imgur.com/hepj9ZS.png"
     *              ),
     *          )
     *      ),
     *      @OA\Response(
     *          response=201,
     *          description="Employer account created successfully",
     *          @OA\JsonContent(
     *              example=
    {
    "error": false,
    "message": "Tạo thành công",
    "data": {
    "employer_account": {
    "username": "emp1",
    "id": 41
    }
    },
    "status_code": 201
    }
     *          )
     *      ),
     *      @OA\Response(
     *          response=400,
     *          description="Employer account creation failed",
     *          ref="#/components/responses/BadRequest"
     *      )
     *  )
     */
    public function createEmployerAccount(SignUpRequest $request): JsonResponse
    {
        try {
            $username = strtolower(str_replace(' ', '', $request->username));
            $password = $request->password;
            $salt_password = $password . env('PASSWORD_SALT');

            if (EmployerAccount::where('username', $username)->first()) {
                return $this->respondBadRequest('Tên đăng nhập đã tồn tại');
            }

            $hashed_password = Hash::make($salt_password);

            $employer_account = new EmployerAccount();
            $employer_account->username = $username;
            $employer_account->password = $hashed_password;
            $employer_account->save();

            $employer_profile = new EmployerProfile();
            $employer_profile->id = $employer_account->id;
            $employer_profile->company_id = $request->user()->id;
            $employer_profile->full_name = $request->full_name ?? $username;
            $employer_profile->save();

            return $this->respondCreated(
                [
                    'employer_account' => $employer_account,
                ]);
        }
        catch (Exception $exception) {
            return $this->respondInternalServerError($exception->getMessage());
        }
    }

    /**
     * @OA\Patch(
     *      path="/employer/password",
     *      tags={"Employer Accounts"},
     *      summary="Update employer account password",
     *      security={{"sanctum":{}}},
     *      @OA\RequestBody(
     *          required=true,
     *          description="Update employer account password request body",
     *          @OA\JsonContent(
     *              example=
    {
    "current_password": "123456",
    "new_password": "1234567",
    "confirm_password": "1234567"
    }
     *          )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Employer account updated successfully",
     *          @OA\JsonContent(
     *              example=
    {
    "error": false,
    "message": "Xử lí thành công",
    "data": {
    "employer_account": {
    "id": 5,
    "username": "tphuong",
    "is_banned": 0,
    "locked_until": null,
    "last_login": null
    }
    },
    "status_code": 200
    }
     *          )
     *      ),
     *      @OA\Response(
     *          response=400,
     *          description="Employer account update password failed",
     *          ref="#/components/responses/BadRequest"
     *      )
     *  )
     */
    public function updatePassword(UpdatePasswordRequest $request): JsonResponse
    {
        try {
            $employer_account = EmployerAccount::where('id', $request->user()->id)->first();

            if (!$employer_account) {
                return $this->respondNotFound();
            }

            $current_password = $request->current_password;
            $new_password = $request->new_password;
            $salt_password = $current_password . env('PASSWORD_SALT');

            if (!Hash::check($salt_password, $employer_account->password)) {
                return $this->respondBadRequest('Mật khẩu hiện tại không đúng');
            }

            $employer_account->password = Hash::make($new_password . env('PASSWORD_SALT'));
            $employer_account->save();

            return $this->respondWithData(
                [
                    'employer_account' => $employer_account,
                ]);
        }
        catch (Exception $exception) {
            return $this->respondInternalServerError($exception->getMessage());
        }
    }

    /**
     * @OA\Put(
     *      path="/employer-accounts/ban/{id}",
     *      tags={"Employer Accounts"},
     *      summary="Ban employer account",
     *      security={{"sanctum":{}}},
     *      @OA\Parameter(
     *          name="id",
     *          description="Employer account id",
     *          required=true,
     *          in="path",
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Employer account banned successfully",
     *          @OA\JsonContent(
     *              example=
    {
    "error": false,
    "message": "Xử lí thành công",
    "data": {
    "employer_account": {
    "id": 1,
    "username": "nva",
    "is_banned": true,
    "locked_until": null,
    "last_login": null
    }
    },
    "status_code": 200
    }
     *          )
     *      ),
     *      @OA\Response(
     *          response=404,
     *          description="Employer account not found",
     *          ref="#/components/responses/NotFound"
     *      )
     *  )
     */
    public function banEmployerAccount(Request $request, string $id): JsonResponse
    {
        try {
            $employer_account = EmployerAccount::where('id', $id)->first();

            if (!$employer_account) {
                return $this->respondNotFound();
            }

            $employer_account->is_banned = true;
            $employer_account->save();

            $employer_account->tokens()->delete();

            return $this->respondWithData(
                [
                    'employer_account' => $employer_account,
                ]);
        }
        catch (Exception $exception) {
            return $this->respondInternalServerError($exception->getMessage());
        }
    }

    /**
     * @OA\Put(
     *      path="/employer-accounts/unban/{id}",
     *      tags={"Employer Accounts"},
     *      summary="Unban employer account",
     *      security={{"sanctum":{}}},
     *      @OA\Parameter(
     *          name="id",
     *          description="Employer account id",
     *          required=true,
     *          in="path",
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Employer account unbanned successfully",
     *          @OA\JsonContent(
     *              example=
    {
    "error": false,
    "message": "Xử lí thành công",
    "data": {
    "employer_account": {
    "id": 1,
    "username": "nva",
    "is_banned": false,
    "locked_until": null,
    "last_login": null
    }
    },
    "status_code": 200
    }
     *          )
     *      ),
     *      @OA\Response(
     *          response=404,
     *          description="Employer account not found",
     *          ref="#/components/responses/NotFound"
     *      )
     *  )
     */
    public function unbanEmployerAccount(Request $request, string $id): JsonResponse
    {
        try {
            $employer_account = EmployerAccount::where('id', $id)->first();

            if (!$employer_account) {
                return $this->respondNotFound();
            }

            $employer_account->is_banned = false;
            $employer_account->save();

            return $this->respondWithData(
                [
                    'employer_account' => $employer_account,
                ]);
        }
        catch (Exception $exception) {
            return $this->respondInternalServerError($exception->getMessage());
        }
    }

    /**
     * @OA\Put(
     *      path="/employer-accounts/lock/{id}",
     *      tags={"Employer Accounts"},
     *      summary="Lock employer account",
     *      security={{"sanctum":{}}},
     *      @OA\Parameter(
     *          name="id",
     *          description="Employer account id",
     *          required=true,
     *          in="path",
     *      ),
     *      @OA\RequestBody(
     *          required=true,
     *          @OA\JsonContent(
     *              example=
    {
    "locked_until": "2023-09-30 00:00:00"
    }
     *          )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Employer account locked successfully",
     *          @OA\JsonContent(
     *              example=
    {
    "error": false,
    "message": "Xử lí thành công",
    "data": {
    "employer_account": {
    "id": 1,
    "username": "nva",
    "is_banned": 0,
    "locked_until": "2023-09-30 00:00:00",
    "last_login": null
    }
    },
    "status_code": 200
    }
     *          )
     *      ),
     *      @OA\Response(
     *          response=404,
     *          description="Employer account not found",
     *          ref="#/components/responses/NotFound"
     *      )
     *  )
     */
    public function lockEmployerAccount(Request $request, string $id): JsonResponse
    {
        try {
            $employer_account = EmployerAccount::where('id', $id)->first();

            if (!$employer_account) {
                return $this->respondNotFound();
            }

            $employer_account->locked_until = $request->locked_until;
            $employer_account->save();

            $employer_account->tokens()->delete();

            return $this->respondWithData(
                [
                    'employer_account' => $employer_account,
                ]);
        }
        catch (Exception $exception) {
            return $this->respondInternalServerError($exception->getMessage());
        }
    }

    /**
     * @OA\Put(
     *      path="/employer-accounts/unlock/{id}",
     *      tags={"Employer Accounts"},
     *      summary="Unlock employer account",
     *      security={{"sanctum":{}}},
     *      @OA\Parameter(
     *          name="id",
     *          description="Employer account id",
     *          required=true,
     *          in="path",
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Employer account unlocked successfully",
     *          @OA\JsonContent(
     *              example=
    {
    "error": false,
    "message": "Xử lí thành công",
    "data": {
    "employer_account": {
    "id": 1,
    "username": "nva",
    "is_banned": 0,
    "locked_until": null,
    "last_login": null
    }
    },
    "status_code": 200
    }
     *          )
     *      ),
     *      @OA\Response(
     *          response=404,
     *          description="Employer account not found",
     *          ref="#/components/responses/NotFound"
     *      )
     *  )
     */
    public function unlockEmployerAccount(Request $request, string $id): JsonResponse
    {
        try {
            $employer_account = EmployerAccount::where('id', $id)->first();

            if (!$employer_account) {
                return $this->respondNotFound();
            }

            $employer_account->locked_until = null;
            $employer_account->save();

            return $this->respondWithData(
                [
                    'employer_account' => $employer_account,
                ]);
        }
        catch (Exception $exception) {
            return $this->respondInternalServerError($exception->getMessage());
        }
    }

    /**
     * @OA\Delete(
     *      path="/employer-accounts/{id}",
     *      tags={"Employer Accounts"},
     *      summary="Delete employer account",
     *      security={{"sanctum":{}}},
     *      @OA\Parameter(
     *          name="id",
     *          description="Employer account id",
     *          required=true,
     *          in="path",
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Employer account deleted successfully",
     *          @OA\JsonContent(
     *              example=
    {
    "error": false,
    "message": "Xoá thành công",
    "data": {
    "employer_account": {
    "id": 41,
    "username": "emp1",
    "is_banned": 0,
    "locked_until": null,
    "last_login": null
    },
    "profile": {
    "id": 41,
    "company_id": 1,
    "full_name": "Họ và tên",
    "avatar": "https://i.imgur.com/hepj9ZS.png"
    }
    },
    "status_code": 200
    }
     *          )
     *      ),
     *      @OA\Response(
     *          response=404,
     *          description="Employer account not found",
     *          ref="#/components/responses/NotFound"
     *      )
     *  )
     */
    public function deleteEmployerAccount(Request $request, string $id): JsonResponse
    {
        try {
            $employer_account = EmployerAccount::where('id', $id)->first();
            $profile = EmployerProfile::where('id', $id)->first();

            if (!$employer_account || !$profile) {
                return $this->respondNotFound();
            }

            $employer_account->delete();
            $profile->delete();

            return $this->respondWithData(
                [
                    'employer_account' => $employer_account,
                    'profile' => $profile,
                ], 'Xoá thành công');
        }
        catch (Exception $exception) {
            return $this->respondInternalServerError($exception->getMessage());
        }
    }
}
