<?php

namespace App\Http\Controllers;

use App\Http\Requests\SignInRequest;
use App\Models\Admin;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthAdminController extends ApiController
{
    private const TOKEN_PREFIX = 'Bearer ';

    /**
     *  @OA\Post(
     *      path="/api/auth-admin/sign-in",
     *      summary="Sign in admin account",
     *      tags={"Auth Admin"},
     *      @OA\RequestBody(
     *          @OA\JsonContent(
     *              example={"username": "Admin", "password": "admin123"}
     *          ),
     *          required=true,
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Sign in successfully",
     *          @OA\JsonContent(
     *              example={
                        "error": false,
                        "message": "Đăng nhập thành công",
                        "data": {
                        "admin": {
                            "id": 1,
                            "username": "Admin",
                            "full_name": "Admin",
                            "avatar": "https://i.imgur.com/1Z1Z1Z1.png",
                            "is_banned": 0,
                            "locked_until": null,
                            "last_login": "2023-05-16T04:03:50.922574Z",
                            "deleted_at": null
                        },
                        "token": "Bearer 1|M03axF5GB3zWOj2Ce0eASES4gF6Yq9iPve97VGzF"
                        },
                        "status_code": 200
     *              }
     *          ),
     *      ),
     *      @OA\Response(
     *          response=400,
     *          description="Bad request",
     *          @OA\JsonContent(
     *              example={
                        "error": true,
                        "message": "Không tìm thấy tên đăng nhập",
                        "data": null,
                        "status_code": 400
     *              }
     *          ),
     *      ),
     *  )
     */
    public function signIn(SignInRequest $request): JsonResponse
    {
        try {
            $username = $request->username;
            $password = $request->password;
            $passwordSalt = $password . env('PASSWORD_SALT');

            $admin = Admin::where('username', $username)->first();

            if (!$admin) {
                return $this->respondBadRequest('Không tìm thấy tên đăng nhập');
            }

            if (!Hash::check($passwordSalt, $admin->password)) {
                return $this->respondBadRequest('Mật khẩu không đúng');
            }

            if ($admin->is_banned) {
                return $this->respondBadRequest('Tài khoản của bạn đã bị chặn');
            }

            if ($admin->locked_until !== null) {
                if (now() < $admin->locked_until) {
                    return $this->respondBadRequest('Tài khoản của bạn đã bị khoá cho đến ' . $admin->locked_until);
                }
            }

            // Generate admin token
            $tokenName = env('ADMIN_AUTH_TOKEN');
            if ($admin->username == 'Admin') {
                $token = $admin->createToken($tokenName);
            }
            else {
                $token = $admin->createToken($tokenName, ['mod']);
            }

            $admin->last_login = now();
            $admin->save();

            return $this->respondWithData(
                [
                    'admin' => $admin,
                    'token' => self::TOKEN_PREFIX . $token->plainTextToken,
                ], 'Đăng nhập thành công');
        } catch (Exception $exception) {
            return $this->respondInternalServerError($exception->getMessage());
        }
    }
}
