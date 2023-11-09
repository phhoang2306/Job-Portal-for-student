<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdatePasswordRequest;
use App\Models\UserAccount;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserAccountController extends ApiController
{
    /**
     *  @OA\Put(
     *      path="/api/user/password",
     *      tags={"User Account"},
     *      summary="Update user password",
     *      security={{"sanctum":{}}},
     *      @OA\Parameter(
     *          name="Accept",
     *          in="header",
     *          description="application/json",
     *          required=false,
     *      ),
     *      @OA\RequestBody(
     *          required=true,
     *          @OA\JsonContent(
     *              example=
    {
    "current_password": 123456,
    "new_password": 123,
    "confirm_password": 123
    }
     *          ),
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Successfully update user password",
     *          @OA\JsonContent(
     *              example=
    {
    "error": false,
    "message": "Successfully updated user account password",
    "data": {
    "user_account": {
    "id": 1,
    "username": "VANDEP123",
    "is_banned": 0,
    "locked_until": null,
    "last_login": null
    }
    },
    "status_code": 200
    }
     *          ),
     *      ),
     *      @OA\Response(
     *          response=400,
     *          description="Bad request",
     *          ref="#/components/responses/BadRequest",
     *      ),
     *  )
     */
    public function updatePassword(UpdatePasswordRequest $request): JsonResponse
    {
        try {
            $user_account = UserAccount::where('id', $request->user()->id)->first();

            if (!isset($user_account)) {
                return $this->respondNotFound();
            }

            $current_password = $request->current_password;
            $new_password = $request->new_password;
            $salt_password = $current_password . env('PASSWORD_SALT');

            if (!Hash::check($salt_password, $user_account->password)) {
                return $this->respondBadRequest(['Mật khẩu hiện tại không đúng']);
            }

            $user_account->password = Hash::make($new_password . env('PASSWORD_SALT'));
            $user_account->save();

            return $this->respondWithData(
                [
                    'user_account' => $user_account,
                ], 'Successfully updated user account password');
        }
        catch (Exception $exception) {
            return $this->respondInternalServerError($exception->getMessage());
        }
    }

    /**
     *  @OA\Get(
     *      path="/api/user-accounts",
     *      tags={"User Account"},
     *      summary="Get all user accounts",
     *      security={{"sanctum":{}}},
     *      @OA\Parameter(
     *          name="count_per_page",
     *          in="query",
     *          description="Number of user accounts per page",
     *          required=false,
     *      ),
     *      @OA\Parameter(
     *          name="Accept",
     *          in="header",
     *          description="application/json",
     *          required=false,
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Successfully retrieved user accounts",
     *          @OA\JsonContent(
     *              example=
    {
    "error": false,
    "message": "Xử lí thành công",
    "data": {
    "user_accounts": {
    "current_page": 1,
    "data": {
    {
    "id": 1,
    "username": "VANDEP123",
    "is_banned": 0,
    "locked_until": null,
    "last_login": null
    }
    },
    "first_page_url": "http://localhost:8000/api/user-accounts?page=1",
    "from": 1,
    "last_page": 50,
    "last_page_url": "http://localhost:8000/api/user-accounts?page=50",
    "links": {
    {
    "url": null,
    "label": "&laquo; Previous",
    "active": false
    },
    {
    "url": "http://localhost:8000/api/user-accounts?page=1",
    "label": "1",
    "active": true
    },
    {
    "url": "http://localhost:8000/api/user-accounts?page=2",
    "label": "2",
    "active": false
    },
    {
    "url": "http://localhost:8000/api/user-accounts?page=3",
    "label": "3",
    "active": false
    },
    {
    "url": "http://localhost:8000/api/user-accounts?page=4",
    "label": "4",
    "active": false
    },
    {
    "url": "http://localhost:8000/api/user-accounts?page=5",
    "label": "5",
    "active": false
    },
    {
    "url": "http://localhost:8000/api/user-accounts?page=6",
    "label": "6",
    "active": false
    },
    {
    "url": "http://localhost:8000/api/user-accounts?page=7",
    "label": "7",
    "active": false
    },
    {
    "url": "http://localhost:8000/api/user-accounts?page=8",
    "label": "8",
    "active": false
    },
    {
    "url": "http://localhost:8000/api/user-accounts?page=9",
    "label": "9",
    "active": false
    },
    {
    "url": "http://localhost:8000/api/user-accounts?page=10",
    "label": "10",
    "active": false
    },
    {
    "url": null,
    "label": "...",
    "active": false
    },
    {
    "url": "http://localhost:8000/api/user-accounts?page=49",
    "label": "49",
    "active": false
    },
    {
    "url": "http://localhost:8000/api/user-accounts?page=50",
    "label": "50",
    "active": false
    },
    {
    "url": "http://localhost:8000/api/user-accounts?page=2",
    "label": "Next &raquo;",
    "active": false
    }
    },
    "next_page_url": "http://localhost:8000/api/user-accounts?page=2",
    "path": "http://localhost:8000/api/user-accounts",
    "per_page": 1,
    "prev_page_url": null,
    "to": 1,
    "total": 50
    }
    },
    "status_code": 200
    }
     *          ),
     *      ),
     *      @OA\Response(
     *          response=400,
     *          description="Bad request",
     *          ref="#/components/responses/BadRequest",
     *      ),
     *  )
     */
    public function getAllUserAccounts(Request $request): JsonResponse
    {
        try {
            $count_per_page = $request->count_per_page ?? 10;
            $order_by = $request->order_by ?? 'id';
            $order_type = $request->order_type ?? 'asc';

            $user_accounts = UserAccount::filter($request, UserAccount::query())
                ->with('profile')
                ->orderBy($order_by, $order_type);

            if ($count_per_page < 1) {
                $user_accounts = $user_accounts->get();
            } else {
                $user_accounts = $user_accounts->paginate($count_per_page);
            }

            if (count($user_accounts) === 0) {
                return $this->respondNotFound();
            }

            return $this->respondWithData(
                [
                    'user_accounts' => $user_accounts,
                ]);
        }
        catch (Exception $exception) {
            return $this->respondInternalServerError($exception->getMessage());
        }
    }

    /**
     *  @OA\Get(
     *      path="/api/user-accounts/{id}",
     *      tags={"User Account"},
     *      summary="Get user account by id",
     *      security={{"sanctum":{}}},
     *      @OA\Parameter(
     *          name="id",
     *          in="path",
     *          description="User account id",
     *          required=true,
     *      ),
     *      @OA\Parameter(
     *          name="Accept",
     *          in="header",
     *          description="application/json",
     *          required=false,
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Successfully get user account by id",
     *          @OA\JsonContent(
     *              example=
    {
    "error": false,
    "message": "Successfully get user account by id",
    "data": {
    "user_account": {
    "current_page": 1,
    "data": {
    {
    "id": 1,
    "username": "VANDEP123",
    "is_banned": 0,
    "locked_until": null,
    "last_login": null
    }
    },
    "first_page_url": "http://localhost:8000/api/user-accounts/1?page=1",
    "from": 1,
    "last_page": 1,
    "last_page_url": "http://localhost:8000/api/user-accounts/1?page=1",
    "links": {
    {
    "url": null,
    "label": "&laquo; Previous",
    "active": false
    },
    {
    "url": "http://localhost:8000/api/user-accounts/1?page=1",
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
    "path": "http://localhost:8000/api/user-accounts/1",
    "per_page": 1,
    "prev_page_url": null,
    "to": 1,
    "total": 1
    }
    },
    "status_code": 200
    }
     *          ),
     *      ),
     *      @OA\Response(
     *          response=404,
     *          description="User account not found",
     *          ref="#/components/responses/NotFound",
     *      ),
     *  )
     */
    public function getUserAccountById(Request $request, string $id): JsonResponse
    {
        try {
            $user_account = UserAccount::where('id', $id)->paginate(1);

            if (count($user_account) === 0) {
                return $this->respondNotFound();
            }

            if (!$request->user()->tokenCan('mod') && $request->user()->id != $id) {
                return $this->respondUnauthorized('Bạn không có quyền truy cập vào tài khoản này');
            }

            return $this->respondWithData(
                [
                    'user_account' => $user_account,
                ], 'Successfully get user account by id');
        }
        catch (Exception $exception) {
            return $this->respondInternalServerError($exception->getMessage());
        }
    }

    /**
     *  @OA\Put(
     *      path="/api/user-accounts/ban/{id}",
     *      tags={"User Account"},
     *      summary="Ban user account",
     *      security={{"sanctum":{}}},
     *      @OA\Parameter(
     *          name="id",
     *          in="path",
     *          description="User account id",
     *          required=true,
     *      ),
     *      @OA\Parameter(
     *          name="Accept",
     *          in="header",
     *          description="application/json",
     *          required=false,
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Successfully update user account by id",
     *          @OA\JsonContent(
     *              example=
    {
    "error": false,
    "message": "Xử lí thành công",
    "data": {
    "user_account": {
    "id": 1,
    "username": "VANDEP123",
    "is_banned": true,
    "locked_until": null,
    "last_login": null
    }
    },
    "status_code": 200
    }
     *          ),
     *      ),
     *      @OA\Response(
     *          response=404,
     *          description="User account not found",
     *          ref="#/components/responses/NotFound",
     *      ),
     *  )
     */
    public function banUserAccount(Request $request, string $id): JsonResponse
    {
        try {
            $user_account = UserAccount::where('id', $id)->first();

            if (!isset($user_account)) {
                return $this->respondNotFound();
            }

            $user_account->is_banned = true;
            $user_account->save();

            $user_account->tokens()->delete();

            return $this->respondWithData(
                [
                    'user_account' => $user_account,
                ]);
        }
        catch (Exception $exception) {
            return $this->respondInternalServerError($exception->getMessage());
        }
    }

    /**
     *  @OA\Put(
     *      path="/api/user-accounts/unban/{id}",
     *      tags={"User Account"},
     *      summary="Unban user account",
     *      security={{"sanctum":{}}},
     *      @OA\Parameter(
     *          name="id",
     *          in="path",
     *          description="User account id",
     *          required=true,
     *      ),
     *      @OA\Parameter(
     *          name="Accept",
     *          in="header",
     *          description="application/json",
     *          required=false,
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Successfully unban user account by id",
     *          @OA\JsonContent(
     *              example=
    {
    "error": false,
    "message": "Xử lí thành công",
    "data": {
    "user_account": {
    "id": 1,
    "username": "VANDEP123",
    "is_banned": false,
    "locked_until": null,
    "last_login": null
    }
    },
    "status_code": 200
    }
     *          ),
     *      ),
     *      @OA\Response(
     *          response=404,
     *          description="User account not found",
     *          ref="#/components/responses/NotFound",
     *      ),
     *  )
     */
    public function unbanUserAccount(Request $request, string $id): JsonResponse
    {
        try {
            $user_account = UserAccount::where('id', $id)->first();

            if (!isset($user_account)) {
                return $this->respondNotFound();
            }

            $user_account->is_banned = false;
            $user_account->save();

            return $this->respondWithData(
                [
                    'user_account' => $user_account,
                ], 'Successfully unbanned user account by id');
        }
        catch (Exception $exception) {
            return $this->respondInternalServerError($exception->getMessage());
        }
    }

    /**
     *  @OA\Put(
     *      path="/api/user-accounts/lock/{id}",
     *      tags={"User Account"},
     *      summary="Lock user account",
     *      security={{"sanctum":{}}},
     *      @OA\Parameter(
     *          name="id",
     *          in="path",
     *          description="User account id",
     *          required=true,
     *      ),
     *      @OA\Parameter(
     *          name="Accept",
     *          in="header",
     *          description="application/json",
     *          required=false,
     *      ),
     *      @OA\RequestBody(
     *          required=true,
     *          @OA\JsonContent(
     *              example=
    {
    "locked_until": "2021-05-20 00:00:00"
    }
     *          ),
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Successfully lock user account by id",
     *          @OA\JsonContent(
     *              example=
    {
    "error": false,
    "message": "Xử lí thành công",
    "data": {
    "user_account": {
    "id": 1,
    "username": "VANDEP123",
    "is_banned": 0,
    "locked_until": "2021-05-20 00:00:00",
    "last_login": null
    }
    },
    "status_code": 200
    }
     *          ),
     *      ),
     *      @OA\Response(
     *          response=404,
     *          description="User account not found",
     *          ref="#/components/responses/NotFound",
     *      ),
     *  )
     */
    public function lockUserAccount(Request $request, string $id): JsonResponse
    {
        try {
            $user_account = UserAccount::where('id', $id)->first();

            if (!isset($user_account)) {
                return $this->respondNotFound();
            }

            $user_account->locked_until = $request->locked_until;
            $user_account->save();

            $user_account->tokens()->delete();

            return $this->respondWithData(
                [
                    'user_account' => $user_account,
                ]);
        }
        catch (Exception $exception) {
            return $this->respondInternalServerError($exception->getMessage());
        }
    }

    /**
     *  @OA\Put(
     *      path="/api/user-accounts/unlock/{id}",
     *      tags={"User Account"},
     *      summary="Unlock user account",
     *      security={{"sanctum":{}}},
     *      @OA\Parameter(
     *          name="id",
     *          in="path",
     *          description="User account id",
     *          required=true,
     *      ),
     *      @OA\Parameter(
     *          name="Accept",
     *          in="header",
     *          description="application/json",
     *          required=false,
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Successfully unlock user account by id",
     *          @OA\JsonContent(
     *              example=
    {
    "error": false,
    "message": "Xử lí thành công",
    "data": {
    "user_account": {
    "id": 1,
    "username": "VANDEP123",
    "is_banned": 1,
    "locked_until": null,
    "last_login": null
    }
    },
    "status_code": 200
    }
     *          ),
     *      ),
     *      @OA\Response(
     *          response=404,
     *          description="User account not found",
     *          ref="#/components/responses/NotFound",
     *      ),
     *  )
     */
    public function unlockUserAccount(Request $request, string $id): JsonResponse
    {
        try {
            $user_account = UserAccount::where('id', $id)->first();

            if (!isset($user_account)) {
                return $this->respondNotFound();
            }

            $user_account->locked_until = null;
            $user_account->save();

            return $this->respondWithData(
                [
                    'user_account' => $user_account,
                ]);
        }
        catch (Exception $exception) {
            return $this->respondInternalServerError($exception->getMessage());
        }
    }

    /**
     *  @OA\Delete(
     *      path="/api/user-accounts/{id}",
     *      tags={"User Account"},
     *      summary="Delete user account",
     *      security={{"sanctum":{}}},
     *      @OA\Parameter(
     *          name="id",
     *          in="path",
     *          description="User account id",
     *          required=true,
     *      ),
     *      @OA\Parameter(
     *          name="Accept",
     *          in="header",
     *          description="application/json",
     *          required=false,
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Successfully delete user account by id",
     *          @OA\JsonContent(
     *              example=
    {
    "error": false,
    "message": "Xoá thành công",
    "data": {
    "user_account": {
    "id": 14,
    "username": "SANG123",
    "is_banned": 0,
    "locked_until": null,
    "last_login": null
    }
    },
    "status_code": 200
    }
     *          ),
     *      ),
     *      @OA\Response(
     *          response=404,
     *          description="User account not found",
     *          ref="#/components/responses/NotFound",
     *      ),
     *  )
     */
    public function deleteUserAccount(Request $request, string $id): JsonResponse
    {
        try {
            $user_account = UserAccount::where('id', $id)->first();

            if (!isset($user_account)) {
                return $this->respondNotFound();
            }

            $user_account->delete();

            return $this->respondWithData(
                [
                    'user_account' => $user_account,
                ], 'Xoá thành công');
        }
        catch (Exception $exception) {
            return $this->respondInternalServerError($exception->getMessage());
        }
    }
}
