<?php

namespace App\Http\Controllers;

use App\Http\Requests\SignInRequest;
use App\Http\Requests\SignUpRequest;
use App\Models\TimeTable;
use App\Models\UserAccount;
use App\Models\UserProfile;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthUserController extends ApiController
{
    private const TOKEN_PREFIX = 'Bearer ';

    /**
     *  @OA\Post(
     *      path="/api/auth-user/sign-up",
     *      summary="Sign up user account",
     *      tags={"Auth User"},
     *      @OA\RequestBody(
     *          @OA\JsonContent(
     *              example={"username": "User", "password": "user123", "confirm_password": "user123"}
     *          ),
     *          required=true,
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Sign up successfully",
     *          @OA\JsonContent(
     *              example={
                        "error": false,
                        "message": "Đăng ký thành công",
                        "data": {
                        "userAccount": {
                        "username": "user1",
                        "id": 72
                        },
                        "token": "Bearer 1|K3e4Q9QPDrZvhY9jklPMSeH2TuDxYBiA3LMWF0Kg"
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
                        "message": "Nhập lại mật khẩu không khớp",
                        "data": null,
                        "status_code": 400
     *              }
     *          ),
     *      ),
     *  )
     */
    public function signUp(SignUpRequest $request): JsonResponse
    {
        try {
            $username = strtolower(str_replace(' ', '', $request->username));
            $password = $request->password;

            $password_salt = $password . env('PASSWORD_SALT');

            if (UserAccount::where('username', $username)->exists()) {
                return $this->respondWithError('Tên đăng nhập đã tồn tại');
            }

            $hashed_password = Hash::make($password_salt);

            $userAccount = new UserAccount();
            $userAccount->username = $username;
            $userAccount->password = $hashed_password;
            $userAccount->save();

            //Create profile
            $profile = new UserProfile();
            $profile->id = $userAccount->id;
            $profile->full_name = $request->full_name ?? $username;
            $profile->save();

            //Create timetable
            $timetable = new Timetable();
            $timetable->user_id = $userAccount->id;
            $timetable->coordinate = $request->coordinate ?? '';
            $timetable->save();

            //Generate user token
            $tokenName = env('USER_AUTH_TOKEN');
            $token = $userAccount->createToken($tokenName, ['user']);

            return $this->respondWithData(
                [
                    'userAccount' => $userAccount,
                    'token' => self::TOKEN_PREFIX . $token->plainTextToken,
                ], 'Đăng ký thành công');
        }
        catch (Exception $exception) {
            return $this->respondWithError($exception->getMessage());
        }
    }

    /**
     *  @OA\Post(
     *      path="/api/auth-user/sign-in",
     *      summary="Sign in user account",
     *      tags={"Auth User"},
     *      @OA\RequestBody(
     *          @OA\JsonContent(
     *              example={"username": "User", "password": "user123"}
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
                            "userAccount": {
                                "id": 1,
                                "username": "VANDEP123",
                                "is_banned": 0,
                                "locked_until": null,
                                "last_login": null,
                                "deleted_at": null
                            },
                            "token": "Bearer 6|YMA2ulLxrCBwLnCK7wdaEDzh4Iam9VQqJUBh17NM"
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
            $password_salt = $password . env('PASSWORD_SALT');

            $userAccount = UserAccount::where('username', $username)->first();

            if (!$userAccount) {
                return $this->respondBadRequest('Không tìm thấy tên đăng nhập');
            }

            if (!Hash::check($password_salt, $userAccount->password)) {
                return $this->respondBadRequest('Mật khẩu không đúng');
            }

            if ($userAccount->is_banned) {
                return $this->respondBadRequest('Tài khoản đã bị chặn');
            }

            if ($userAccount->locked_until !== null) {
                if (now() < $userAccount->locked_until) {
                    return $this->respondBadRequest('Tài khoản đã bị khoá cho đến ' . $userAccount->locked_until);
                }
            }

            //Generate user token
            $tokenName = env('USER_AUTH_TOKEN');
            $token = $userAccount->createToken($tokenName, ['user']);

            $userAccount->last_login = now();
            $userAccount->save();

            return $this->respondWithData(
                [
                    'userAccount' => $userAccount,
                    'token' => self::TOKEN_PREFIX . $token->plainTextToken,
                ], 'Đăng nhập thành công');
        }
        catch (Exception $exception) {
            return $this->respondWithError($exception->getMessage());
        }
    }
}
