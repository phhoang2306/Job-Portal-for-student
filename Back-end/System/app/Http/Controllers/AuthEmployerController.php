<?php

namespace App\Http\Controllers;

use App\Http\Requests\SignInRequest;
use App\Models\EmployerAccount;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthEmployerController extends ApiController
{
    const TOKEN_PREFIX = 'Bearer ';

    /**
     *  @OA\Post(
     *      path="/api/auth-employer/sign-in",
     *      tags={"Auth Employer"},
     *      summary="Sign in employer account",
     *      @OA\RequestBody(
     *          required=true,
     *          @OA\JsonContent(
     *              example={"username": "Employer", "password": "employer123"}
     *          ),
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Sign in successfully",
     *          @OA\JsonContent(
     *              example={
                        "error": false,
                        "message": "Đăng nhập thành công",
                        "data": {
                            "employerAccount": {
                                "id": 1,
                                "username": "NguyenVanA",
                                "is_banned": 0,
                                "locked_until": null,
                                "last_login": "2023-05-18T06:19:05.366279Z",
                                "deleted_at": null
                            },
                            "token": "Bearer 8|nP6NtzbU1PF5935pQjTc2PgjCkmAYt0iPIPRq3kU"
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
                        "message": "Tên đăng nhập không tồn tại",
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

            $employerAccount = EmployerAccount::where('username', $username)->first();

            if (!$employerAccount) {
                return $this->respondBadRequest('Không tìm thấy tên đăng nhập');
            }

            if (!Hash::check($passwordSalt, $employerAccount->password)) {
                return $this->respondBadRequest('Mật khẩu không đúng');
            }

            if ($employerAccount->is_banned) {
                return $this->respondBadRequest('Tài khoản đã bị chặn');
            }

            if ($employerAccount->locked_until !== null) {
                if (now() < $employerAccount->locked_until) {
                    return $this->respondBadRequest('Tài khoản đã bị khoá cho đến ' . $employerAccount->locked_until);
                }
            }

            // Generate employer token
            $tokenName = env('EMPLOYER_AUTH_TOKEN');
            $token = $employerAccount->createToken($tokenName, ['employer']);

            $employerAccount->last_login = now();
            $employerAccount->save();

            return $this->respondWithData(
                [
                    'employerAccount' => $employerAccount,
                    'token' => self::TOKEN_PREFIX . $token->plainTextToken,
                ], 'Đăng nhập thành công');
        }
        catch (Exception $exception) {
            return $this->respondInternalServerError($exception->getMessage());
        }
    }
}
