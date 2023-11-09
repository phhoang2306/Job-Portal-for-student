<?php

namespace App\Http\Controllers;

use App\Http\Requests\CompanySignUpRequest;
use App\Http\Requests\SignInRequest;
use App\Models\CompanyAccount;
use App\Models\CompanyProfile;
use App\Models\EmployerAccount;
use App\Models\EmployerProfile;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Hash;

class AuthCompanyController extends ApiController
{
    private const TOKEN_PREFIX = 'Bearer ';

    /**
     *  @OA\Post(
     *      path="/api/auth-company/sign-up",
     *      summary="Sign up company account",
     *      tags={"Auth Company"},
     *      @OA\RequestBody(
     *          @OA\JsonContent(
     *              example={"username": "Company", "password": "company123", "confirm_password": "company123"}
     *          ),
     *          required=true,
     *      ),
     *      @OA\Response(
     *          response=201,
     *          description="Sign up successfully",
     *          @OA\JsonContent(
     *              example={
                        "error": false,
                        "message": "Đăng ký thành công",
                        "data": {
                            "companyAccount": {
                                "username": "comp2",
                                "id": 12
                        },
                        "token": "Bearer 4|sacseaziyLhceKddmZw4KnRjvOq0an3XTJ22yGEz"
                        },
                        "status_code": 201
                    }
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
                    }
     *          ),
     *      ),
     *  )
     */
    public function signUp(CompanySignUpRequest $request): JsonResponse
    {
        try {
            $username = strtolower(str_replace(' ', '', $request->username));
            $password = $request->password;
            $password_salt = $password . env('PASSWORD_SALT');

            if (CompanyAccount::where('username', $username)->exists()) {
                return $this->respondBadRequest('Tên đăng nhập đã tồn tại');
            }

            $hashed_password = Hash::make($password_salt);

            $company_account = new CompanyAccount();
            $company_account->username = $username;
            $company_account->password = $hashed_password;
            $company_account->save();

            $profile = new CompanyProfile();
            $profile->id = $company_account->id;
            $profile->name = $request->name ?? $username;
            $profile->description = $request->description ?? '';
            $profile->site = $request->site ?? '';
            $profile->address = $request->address ?? '';
            $profile->size = $request->size ?? '';
            $profile->phone = $request->phone ?? '';
            $profile->email = $request->email;
            $profile->save();

            //Create a Default employer account
            $employer_account = new EmployerAccount();
            $employer_account->username = 'default';
            $employer_account->password = Hash::make(env('INIT_PASSWORD') . env('PASSWORD_SALT'));
            $employer_account->save();

            $employer_profile = new EmployerProfile();
            $employer_profile->id = $employer_account->id;
            $employer_profile->full_name = $profile->name . ' - Default Employer';
            $employer_profile->company_id = $company_account->id;
            $employer_profile->save();

            return $this->respondWithData(
                [
                    'companyAccount' => $company_account,
                    'defaultEmployerAccount' => $employer_account,
                ], 'Đăng ký thành công');
        } catch (Exception $exception) {
            return $this->respondInternalServerError($exception->getMessage());
        }
    }

    /**
     *  @OA\Post(
     *      path="/api/auth-company/sign-in",
     *      summary="Sign in company account",
     *      tags={"Auth Company"},
     *      @OA\RequestBody(
     *          @OA\JsonContent(
     *              example={"username": "Company", "password": "company123"}
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
                            "companyAccount": {
                                "id": 12,
                                "username": "comp2",
                                "is_verified": 0,
                                "is_banned": 0,
                                "locked_until": null,
                                "last_login": "2023-05-18T06:02:37.629646Z",
                                "deleted_at": null
                            },
                            "token": "Bearer 7|gPmtRFws2KolgR6PqIXLO2LUEvS1Tqi1GcA8vG1k"
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
                    }
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

            $companyAccount = CompanyAccount::where('username', $username)->first();

            if (!$companyAccount) {
                return $this->respondBadRequest('Không tìm thấy tên đăng nhập');
            }

            if (!Hash::check($passwordSalt, $companyAccount->password)) {
                return $this->respondBadRequest('Mật khẩu không đúng');
            }

//            if (!$companyAccount->is_verified) {
//                return $this->respondBadRequest('Tài khoản chưa được xác thực');
//            }

            if ($companyAccount->is_banned) {
                return $this->respondBadRequest('Tài khoản đã bị chặn');
            }

            if ($companyAccount->locked_until !== null) {
                if (now() < $companyAccount->locked_until) {
                    return $this->respondBadRequest('Tài khoản đã bị khoá cho đến ' . $companyAccount->locked_until);
                }
            }

            // Generate company token
            $tokenName = env('COMPANY_AUTH_TOKEN');
            $token = $companyAccount->createToken($tokenName, ['company']);

            $companyAccount->last_login = now();
            $companyAccount->save();

            return $this->respondWithData(
                [
                    'companyAccount' => $companyAccount,
                    'token' => self::TOKEN_PREFIX . $token->plainTextToken,
                ], 'Đăng nhập thành công');
        } catch (Exception $exception) {
            return $this->respondInternalServerError($exception->getMessage());
        }
    }
}
