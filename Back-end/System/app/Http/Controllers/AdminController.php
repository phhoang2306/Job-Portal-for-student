<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateModRequest;
use App\Http\Requests\SignUpRequest;
use App\Http\Requests\UpdateModRequest;
use App\Http\Requests\UpdatePasswordRequest;
use App\Models\Admin;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class AdminController extends ApiController
{
    /**
     *  @OA\Get(
     *      path="/admin",
     *      tags={"Admin"},
     *      summary="Get admin account",
     *      security={{"sanctum":{}}},
     *      @OA\Response(
     *          response=200,
     *          description="Get admin account successfully",
     *          @OA\JsonContent(
     *              example=
    {
    "error": false,
    "message": "Xử lí thành công",
    "data": {
    "admin": {
    "id": 1,
    "username": "Admin",
    "full_name": "Admin",
    "avatar": "https://i.imgur.com/1Z1Z1Z1.png",
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
     *          description="Admin account not found",
     *          ref="#/components/responses/NotFound"
     *      )
     *  )
     */
    public function getAdmin(Request $request): JsonResponse
    {
        try {
            $admin = Admin::where('username', 'Admin')->first();

            if (!$admin) {
                return $this->respondNotFound();
            }

            return $this->respondWithData(
                [
                    'admin' => $admin,
                ]);
        }
        catch (Exception $exception) {
            return $this->respondInternalServerError($exception->getMessage());
        }
    }

    /**
     *  @OA\Post(
     *      path="/admin/mod",
     *      tags={"Admin"},
     *      summary="Create mod account",
     *      security={{"sanctum":{}}},
     *      @OA\RequestBody(
     *          required=true,
     *          @OA\JsonContent(
     *              required={"username", "password"},
     *              example=
    {
    "username": "mod1",
    "password": "123456"
    }
     *          )
     *      ),
     *      @OA\Response(
     *          response=201,
     *          description="Mod account created successfully",
     *          @OA\JsonContent(
     *              example=
    {
    "error": false,
    "message": "Tạo thành công",
    "data": {
    "modAccount": {
    "username": "mod1",
    "id": 2
    }
    },
    "status_code": 201
    }
     *          )
     *      ),
     *      @OA\Response(
     *          response=400,
     *          description="Username already exists",
     *          ref="#/components/responses/BadRequest"
     *      )
     *  )
     */
    public function createModAccount(CreateModRequest $request): JsonResponse
    {
        try {
            $username = strtolower(str_replace(' ', '', $request->username));
            $password = $request->password;
            $full_name = $request->full_name;
            $avatar = $request->avatar ?? env('DEFAULT_LOGO_URL');
            $password_salt = $password . env('PASSWORD_SALT');

            if (Admin::where('username', $username)->exists()) {
                return $this->respondBadRequest('Tên đăng nhập đã tồn tại');
            }

            $hashedPassword = Hash::make($password_salt);

            $modAccount = new Admin();
            $modAccount->username = $username;
            $modAccount->password = $hashedPassword;
            $modAccount->full_name = $full_name;
            $modAccount->avatar = $avatar;
            $modAccount->save();

            return $this->respondCreated(
                [
                    'modAccount' => $modAccount,
                ]);
        }
        catch (Exception $exception) {
            return $this->respondInternalServerError($exception->getMessage());
        }
    }

    /**
     *  @OA\Get(
     *      path="/mods",
     *      tags={"Admin"},
     *      summary="Get all mods",
     *      security={{"sanctum":{}}},
     *      @OA\Parameter(
     *          name="count_per_page",
     *          description="Number of mods per page",
     *          required=false,
     *          in="query",
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Get all mods successfully",
     *          @OA\JsonContent(
     *              example=
    {
    "error": false,
    "message": "Xử lí thành công",
    "data": {
    "mods": {
    "current_page": 1,
    "data": {
    {
    "id": 2,
    "username": "mod1",
    "full_name": null,
    "avatar": "https://i.imgur.com/hepj9ZS.png",
    "is_banned": 0,
    "locked_until": null,
    "last_login": null
    }
    },
    "first_page_url": "http://127.0.0.1:8000/api/admin/mods?page=1",
    "from": 1,
    "last_page": 1,
    "last_page_url": "http://127.0.0.1:8000/api/admin/mods?page=1",
    "links": {
    {
    "url": null,
    "label": "&laquo; Previous",
    "active": false
    },
    {
    "url": "http://127.0.0.1:8000/api/admin/mods?page=1",
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
    "path": "http://127.0.0.1:8000/api/admin/mods",
    "per_page": 10,
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
     *          description="No mod found",
     *          ref="#/components/responses/NotFound"
     *      )
     *  )
     */
    public function getAllMods(Request $request): JsonResponse
    {
        try {
            $count_per_page = $request->count_per_page ?? 10;
            $order_by = $request->order_by ?? 'id';
            $order_type = $request->order_type ?? 'asc';

            $mods = Admin::filter($request, Admin::query())
                ->where('username', '!=', 'Admin')
                ->orderBy($order_by, $order_type);

            if ($count_per_page < 1) {
                $mods = $mods->get();
            } else {
                $mods = $mods->paginate($count_per_page);
            }

            if (count($mods) === 0) {
                return $this->respondNotFound();
            }

            return $this->respondWithData(
                [
                    'mods' => $mods,
                ]);
        }
        catch (Exception $exception) {
            return $this->respondInternalServerError($exception->getMessage());
        }
    }

    /**
     *  @OA\Get(
     *      path="/mods/{id}",
     *      tags={"Admin"},
     *      summary="Get mod by id",
     *      security={{"sanctum":{}}},
     *      @OA\Parameter(
     *          name="id",
     *          description="Mod id",
     *          required=true,
     *          in="path",
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Get mod successfully",
     *          @OA\JsonContent(
     *              example=
    {
    "error": false,
    "message": "Xử lí thành công",
    "data": {
    "mod": {
    "id": 2,
    "username": "mod1",
    "full_name": null,
    "avatar": "https://i.imgur.com/hepj9ZS.png",
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
     *          description="Mod not found",
     *          ref="#/components/responses/NotFound"
     *      )
     *  )
     */
    public function getModById(string $id): JsonResponse
    {
        try {
            $mod = Admin::where('id', $id)->first();

            if (!$mod) {
                return $this->respondNotFound();
            }

            return $this->respondWithData(
                [
                    'mod' => $mod,
                ]);
        }
        catch (Exception $exception) {
            return $this->respondInternalServerError($exception->getMessage());
        }
    }

    /**
     *  @OA\Put(
     *      path="/mods/{id}",
     *      tags={"Admin"},
     *      summary="Update mod account",
     *      security={{"sanctum":{}}},
     *      @OA\Parameter(
     *          name="id",
     *          description="Mod id",
     *          required=true,
     *          in="path",
     *      ),
     *      @OA\RequestBody(
     *          required=true,
     *          @OA\JsonContent(
     *              example=
    {
    "full_name": "mod 1",
    }
     *          ),
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Update mod account successfully",
     *          @OA\JsonContent(
     *              example=
    {
    "error": false,
    "message": "Xử lí thành công",
    "data": {
    "mod": {
    "id": 2,
    "username": "mod1",
    "full_name": "mod 1",
    "avatar": "https://i.imgur.com/hepj9ZS.png",
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
     *          description="Mod not found",
     *          ref="#/components/responses/NotFound"
     *      )
     *  )
     */
    public function updateModAccount(UpdateModRequest $request, string $id): JsonResponse
    {
        try {
            $mod = Admin::where('id', $id)->first();

            if (!$mod) {
                return $this->respondNotFound();
            }

            if (!$request->user()->tokenCan('*') && $request->user()->id !== $mod->id) {
                return $this->respondForbidden('Bạn không có quyền thực hiện hành động này');
            }

            $mod->full_name = $request->full_name ?? $mod->full_name;

            if ($request->hasFile('avatar')) {
                $file = $request->file('avatar');
                $file_name = $file->getClientOriginalName();
                $file_name = str_replace(' ', '_', $file_name);
                $file_name = preg_replace('/[^A-Za-z0-9\-\.]/', '', $file_name);
                $file_name = time() . '_' . $file_name;

                $path = Storage::disk('s3')->putFileAs(
                    'admin_avatar',
                    $file,
                    $file_name,
                );
                $url = Storage::disk('s3')->url($path);

                if (!$path || !$url) {
                    return $this->respondInternalServerError('Lỗi khi upload ảnh');
                }

                $mod->avatar = $url;
            }

            $mod->save();

            return $this->respondWithData(
                [
                    'mod' => $mod,
                ]);
        }
        catch (Exception $exception) {
            return $this->respondInternalServerError($exception->getMessage());
        }
    }

    /**
     *  @OA\Put(
     *      path="/admin/password",
     *      tags={"Admin"},
     *      summary="Update admin or mod password",
     *      security={{"sanctum":{}}},
     *      @OA\RequestBody(
     *          required=true,
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
     *          description="Update password successfully",
     *          @OA\JsonContent(
     *              example=
    {
    "error": false,
    "message": "Xử lí thành công",
    "data": {
    "account": {
    "id": 1,
    "username": "Admin",
    "full_name": "adminn",
    "avatar": "https://i.imgur.com/1Z1Z1Z1.png",
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
     *          description="Bad request",
     *          ref="#/components/responses/BadRequest"
     *      )
     *  )
     */
    public function updatePassword(UpdatePasswordRequest $request): JsonResponse
    {
        try {
            $account = Admin::where('id', $request->user()->id)->first();

            if (!$account) {
                return $this->respondNotFound();
            }

            $current_password = $request->current_password;
            $new_password = $request->new_password;
            $salt_password = $current_password . env('PASSWORD_SALT');

            if (!Hash::check($salt_password, $account->password)) {
                return $this->respondBadRequest('Mật khẩu cũ không đúng');
            }

            $account->password = Hash::make($new_password . env('PASSWORD_SALT'));
            $account->save();

            return $this->respondWithData(
                [
                    'account' => $account,
                ]);
        }
        catch (Exception $exception) {
            return $this->respondInternalServerError($exception->getMessage());
        }
    }

    /**
     *  @OA\Delete(
     *      path="/admin/mod/{id}",
     *      tags={"Admin"},
     *      summary="Delete mod account",
     *      security={{"sanctum":{}}},
     *      @OA\Parameter(
     *          name="id",
     *          description="Mod id",
     *          required=true,
     *          in="path"
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Delete mod account successfully",
     *          @OA\JsonContent(
     *              example=
    {
    "error": false,
    "message": "Xóa thành công",
    "data": {
    "mod": {
    "id": 2,
    "username": "mod1",
    "full_name": "mod 1",
    "avatar": "https://i.imgur.com/hepj9ZS.png",
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
     *          description="Mod not found",
     *          ref="#/components/responses/NotFound"
     *      )
     *  )
     */
    public function deleteModAccount(string $id): JsonResponse
    {
        try {
            $mod = Admin::where('id', $id)->first();

            if (!$mod) {
                return $this->respondNotFound();
            }

            $mod->delete();

            return $this->respondWithData(
                [
                    'mod' => $mod,
                ], 'Xóa thành công');
        }
        catch (Exception $exception) {
            return $this->respondInternalServerError($exception->getMessage());
        }
    }

    /**
     *  @OA\Put(
     *      path="/admin/mod/ban/{id}",
     *      tags={"Admin"},
     *      summary="Ban mod account",
     *      security={{"sanctum":{}}},
     *      @OA\Parameter(
     *          name="id",
     *          description="Mod id",
     *          required=true,
     *          in="path"
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Ban mod account successfully",
     *          @OA\JsonContent(
     *              example=
    {
    "error": false,
    "message": "Xử lí thành công",
    "data": {
    "mod": {
    "id": 2,
    "username": "mod1",
    "full_name": "mod 1",
    "avatar": "https://i.imgur.com/hepj9ZS.png",
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
     *          response=403,
     *          description="Forbidden",
     *          ref="#/components/responses/Forbidden"
     *      )
     *  )
     */
    public function banModAccount(string $id): JsonResponse
    {
        try {
            $mod = Admin::where('id', $id)->first();

            if (!$mod) {
                return $this->respondNotFound();
            }

            if ($mod->username === 'Admin') {
                return $this->respondForbidden('Không thể khóa tài khoản admin');
            }

            $mod->is_banned = true;
            $mod->save();

            $mod->tokens()->delete();

            return $this->respondWithData(
                [
                    'mod' => $mod,
                ]);
        }
        catch (Exception $exception) {
            return $this->respondInternalServerError($exception->getMessage());
        }
    }

    /**
     *  @OA\Put(
     *      path="/admin/mod/unban/{id}",
     *      tags={"Admin"},
     *      summary="Unban mod account",
     *      security={{"sanctum":{}}},
     *      @OA\Parameter(
     *          name="id",
     *          description="Mod id",
     *          required=true,
     *          in="path"
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Unban mod account successfully",
     *          @OA\JsonContent(
     *              example=
    {
    "error": false,
    "message": "Xử lí thành công",
    "data": {
    "mod": {
    "id": 2,
    "username": "mod1",
    "full_name": "mod 1",
    "avatar": "https://i.imgur.com/hepj9ZS.png",
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
     *          response=403,
     *          description="Forbidden",
     *          ref="#/components/responses/Forbidden"
     *      )
     *  )
     */
    public function unbanModAccount(string $id): JsonResponse
    {
        try {
            $mod = Admin::where('id', $id)->first();

            if (!$mod) {
                return $this->respondNotFound();
            }

            $mod->is_banned = false;
            $mod->save();

            return $this->respondWithData(
                [
                    'mod' => $mod,
                ]);
        }
        catch (Exception $exception) {
            return $this->respondInternalServerError($exception->getMessage());
        }
    }

    /**
     *  @OA\Put(
     *      path="/admin/mod/lock/{id}",
     *      tags={"Admin"},
     *      summary="Lock mod account",
     *      security={{"sanctum":{}}},
     *      @OA\Parameter(
     *          name="id",
     *          description="Mod id",
     *          required=true,
     *          in="path"
     *      ),
     *      @OA\RequestBody(
     *          required=true,
     *          description="Lock mod account request body",
     *          @OA\JsonContent(
     *              required={"locked_until"},
     *              example=
    {
    "locked_until": "2024-05-31 00:00:00"
    }
     *          )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Lock mod account successfully",
     *          @OA\JsonContent(
     *              example=
    {
    "error": false,
    "message": "Xử lí thành công",
    "data": {
    "mod": {
    "id": 2,
    "username": "mod1",
    "full_name": "mod 1",
    "avatar": "https://i.imgur.com/hepj9ZS.png",
    "is_banned": 0,
    "locked_until": "2024-05-31 00:00:00",
    "last_login": null
    }
    },
    "status_code": 200
    }
     *          )
     *      ),
     *      @OA\Response(
     *          response=403,
     *          description="Forbidden",
     *          ref="#/components/responses/Forbidden"
     *      )
     *  )
     */
    public function lockModAccount(Request $request, string $id): JsonResponse
    {
        try {
            $mod = Admin::where('id', $id)->first();

            if (!$mod) {
                return $this->respondNotFound();
            }

            if ($mod->username === 'Admin') {
                return $this->respondForbidden('Không thể khóa tài khoản admin');
            }

            $mod->locked_until = $request->locked_until;
            $mod->save();

            $mod->tokens()->delete();

            return $this->respondWithData(
                [
                    'mod' => $mod,
                ]);
        }
        catch (Exception $exception) {
            return $this->respondInternalServerError($exception->getMessage());
        }
    }

    /**
     *  @OA\Put(
     *      path="/admin/mod/unlock/{id}",
     *      tags={"Admin"},
     *      summary="Unlock mod account",
     *      security={{"sanctum":{}}},
     *      @OA\Parameter(
     *          name="id",
     *          description="Mod id",
     *          required=true,
     *          in="path"
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Unlock mod account successfully",
     *          @OA\JsonContent(
     *              example=
    {
    "error": false,
    "message": "Xử lí thành công",
    "data": {
    "mod": {
    "id": 2,
    "username": "mod1",
    "full_name": "mod 1",
    "avatar": "https://i.imgur.com/hepj9ZS.png",
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
     *          response=403,
     *          description="Forbidden",
     *          ref="#/components/responses/Forbidden"
     *      )
     *  )
     */
    public function unlockModAccount(string $id): JsonResponse
    {
        try {
            $mod = Admin::where('id', $id)->first();

            if (!$mod) {
                return $this->respondNotFound();
            }

            $mod->locked_until = null;
            $mod->save();

            return $this->respondWithData(
                [
                    'mod' => $mod,
                ]);
        }
        catch (Exception $exception) {
            return $this->respondInternalServerError($exception->getMessage());
        }
    }
}
