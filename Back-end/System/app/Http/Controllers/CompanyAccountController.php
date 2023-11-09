<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdatePasswordRequest;
use App\Models\CompanyAccount;
use App\Models\CompanyProfile;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class CompanyAccountController extends ApiController
{
    /**
     *  @OA\Get(
     *      path="/api/company-accounts",
     *      tags={"Company Account"},
     *      summary="Get all company accounts",
     *      security={{"sanctum":{}}},
     *      @OA\Parameter(
     *          name="count_per_page",
     *          in="query",
     *          description="Count per page",
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
     *          description="Successfully get all company accounts",
     *          @OA\JsonContent(
     *              example=
    {
    "error": false,
    "message": "Xử lí thành công",
    "data": {
    "company_accounts": {
    "current_page": 1,
    "data": {
    {
    "id": 1,
    "username": "ltd10",
    "is_verified": 1,
    "is_banned": 0,
    "locked_until": null,
    "last_login": null,
    "profile": {
    "id": 1,
    "name": "TV TPI CO., LTD",
    "logo": "https://i.imgur.com/hepj9ZS.png",
    "description": "['TV TPI là tổ chức luôn mang sứ mệnh cung cấp cho người sử dụng thuốc các sản phẩm giá trị và chất lượng được đăng ký tại Châu Âu với giá thành hợp lý. Bên cạnh sự phát triển kinh doanh, chúng tôi luôn ý thức được rằng mỗi nhân sự là một mắt xích quan trọng, là nhân tài của tổ chức. Do đó, TV TPI luôn tìm kiếm những con người mong muốn phát triển bản thân, không ngừng học hỏi và góp phần tạo nên sự phát triển bền vững của công ty.', 'Sứ mệnh', 'TV TPI ra đời nhằm phụng sự cho người sử dụng thuốc tại Việt Nam và các nước Đông Nam Á qua việc cung cấp cho người sử dụng thuốc các sản phẩm giá trị và chất lượng được đăng ký tại Châu Âu với giá thành hợp lý.', 'Tầm nhìn', 'Đưa TV TPI nằm trong Top 1.000 công ty Dược vào năm 2027. Giữ vững là Công ty số 1 cung cấp các dịch vụ EU GMP cho tất cả các đối tác hoạt động tại Việt Nam. Là Công ty đầu tiên tại Việt Nam sở hữu số đăng ký thuốc “Generics” được sản xuất tại Việt Nam nhiều nhất tại Châu Âu.', 'Công Ty hoạt động trong lĩnh vực Kinh Doanh và Phân Phối các sản phẩm:']",
    "site": "http://tvtpi.com.vn/",
    "address": "72 Bình Giã, Phường 13, Quận Tân Bình, TP. HCM",
    "size": "25-99"
    }
    }
    },
    "first_page_url": "http://127.0.0.1:8000/api/company-accounts?page=1",
    "from": 1,
    "last_page": 20,
    "last_page_url": "http://127.0.0.1:8000/api/company-accounts?page=20",
    "links": {
    {
    "url": null,
    "label": "&laquo; Previous",
    "active": false
    },
    {
    "url": "http://127.0.0.1:8000/api/company-accounts?page=1",
    "label": "1",
    "active": true
    },
    {
    "url": "http://127.0.0.1:8000/api/company-accounts?page=2",
    "label": "2",
    "active": false
    },
    {
    "url": "http://127.0.0.1:8000/api/company-accounts?page=3",
    "label": "3",
    "active": false
    },
    {
    "url": "http://127.0.0.1:8000/api/company-accounts?page=4",
    "label": "4",
    "active": false
    },
    {
    "url": "http://127.0.0.1:8000/api/company-accounts?page=5",
    "label": "5",
    "active": false
    },
    {
    "url": "http://127.0.0.1:8000/api/company-accounts?page=6",
    "label": "6",
    "active": false
    },
    {
    "url": "http://127.0.0.1:8000/api/company-accounts?page=7",
    "label": "7",
    "active": false
    },
    {
    "url": "http://127.0.0.1:8000/api/company-accounts?page=8",
    "label": "8",
    "active": false
    },
    {
    "url": "http://127.0.0.1:8000/api/company-accounts?page=9",
    "label": "9",
    "active": false
    },
    {
    "url": "http://127.0.0.1:8000/api/company-accounts?page=10",
    "label": "10",
    "active": false
    },
    {
    "url": null,
    "label": "...",
    "active": false
    },
    {
    "url": "http://127.0.0.1:8000/api/company-accounts?page=19",
    "label": "19",
    "active": false
    },
    {
    "url": "http://127.0.0.1:8000/api/company-accounts?page=20",
    "label": "20",
    "active": false
    },
    {
    "url": "http://127.0.0.1:8000/api/company-accounts?page=2",
    "label": "Next &raquo;",
    "active": false
    }
    },
    "next_page_url": "http://127.0.0.1:8000/api/company-accounts?page=2",
    "path": "http://127.0.0.1:8000/api/company-accounts",
    "per_page": 1,
    "prev_page_url": null,
    "to": 1,
    "total": 20
    }
    },
    "status_code": 200
    }
     *          ),
     *      ),
     *      @OA\Response(
     *          response=404,
     *          description="No company account found",
     *          ref="#/components/responses/NotFound",
     *      ),
     *  )
     */
    public function getCompanyAccounts(Request $request): JsonResponse
    {
        try {
            $count_per_page = $request->count_per_page ?? 10;
            $order_by = $request->order_by ?? 'id';
            $order_type = $request->order_type ?? 'asc';

            $company_accounts = CompanyAccount::filter($request, CompanyAccount::query())
                ->with('profile')
                ->orderBy($order_by, $order_type);

            if ($count_per_page < 1) {
                $company_accounts = $company_accounts->get();
            } else {
                $company_accounts = $company_accounts->paginate($count_per_page);
            }

            if (count($company_accounts) === 0) {
                return $this->respondNotFound();
            }

            return $this->respondWithData(
                [
                    'company_accounts' => $company_accounts
                ]);
        }
        catch (Exception $exception) {
            return $this->respondInternalServerError($exception->getMessage());
        }
    }

    /**
     *  @OA\Get(
     *      path="/api/company-accounts/{id}",
     *      tags={"Company Account"},
     *      summary="Get company account by id",
     *      security={{"sanctum":{}}},
     *      @OA\Parameter(
     *          name="id",
     *          in="path",
     *          description="Company account id",
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
     *          description="Successfully get company account by id",
     *          @OA\JsonContent(
     *              example=
    {
    "error": false,
    "message": "Xử lí thành công",
    "data": {
    "company_account": {
    "current_page": 1,
    "data": {
    {
    "id": 1,
    "username": "ltd10",
    "is_verified": 1,
    "is_banned": 0,
    "locked_until": null,
    "last_login": null,
    "profile": {
    "id": 1,
    "name": "TV TPI CO., LTD",
    "logo": "https://i.imgur.com/hepj9ZS.png",
    "description": "['TV TPI là tổ chức luôn mang sứ mệnh cung cấp cho người sử dụng thuốc các sản phẩm giá trị và chất lượng được đăng ký tại Châu Âu với giá thành hợp lý. Bên cạnh sự phát triển kinh doanh, chúng tôi luôn ý thức được rằng mỗi nhân sự là một mắt xích quan trọng, là nhân tài của tổ chức. Do đó, TV TPI luôn tìm kiếm những con người mong muốn phát triển bản thân, không ngừng học hỏi và góp phần tạo nên sự phát triển bền vững của công ty.', 'Sứ mệnh', 'TV TPI ra đời nhằm phụng sự cho người sử dụng thuốc tại Việt Nam và các nước Đông Nam Á qua việc cung cấp cho người sử dụng thuốc các sản phẩm giá trị và chất lượng được đăng ký tại Châu Âu với giá thành hợp lý.', 'Tầm nhìn', 'Đưa TV TPI nằm trong Top 1.000 công ty Dược vào năm 2027. Giữ vững là Công ty số 1 cung cấp các dịch vụ EU GMP cho tất cả các đối tác hoạt động tại Việt Nam. Là Công ty đầu tiên tại Việt Nam sở hữu số đăng ký thuốc “Generics” được sản xuất tại Việt Nam nhiều nhất tại Châu Âu.', 'Công Ty hoạt động trong lĩnh vực Kinh Doanh và Phân Phối các sản phẩm:']",
    "site": "http://tvtpi.com.vn/",
    "address": "72 Bình Giã, Phường 13, Quận Tân Bình, TP. HCM",
    "size": "25-99"
    }
    }
    },
    "first_page_url": "http://127.0.0.1:8000/api/company-accounts/1?page=1",
    "from": 1,
    "last_page": 1,
    "last_page_url": "http://127.0.0.1:8000/api/company-accounts/1?page=1",
    "links": {
    {
    "url": null,
    "label": "&laquo; Previous",
    "active": false
    },
    {
    "url": "http://127.0.0.1:8000/api/company-accounts/1?page=1",
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
    "path": "http://127.0.0.1:8000/api/company-accounts/1",
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
     *          description="Company account not found",
     *          ref="#/components/responses/NotFound",
     *      ),
     *  )
     */
    public function getCompanyAccountById(Request $request, string $id): JsonResponse
    {
        try {
            $company_account = CompanyAccount::where('id', $id)
                ->with('profile')
                ->first();

            if (!$company_account) {
                return $this->respondNotFound();
            }

            if (!$request->user()->tokenCan('mod') && $request->user()->id != $id) {
                return $this->respondForbidden('Bạn không có quyền truy cập vào tài khoản này');
            }

            return $this->respondWithData(
                [
                    'company_account' => $company_account
                ]);
        }
        catch (Exception $exception) {
            return $this->respondInternalServerError($exception->getMessage());
        }
    }

    /**
     *  @OA\Put(
     *      path="/api/company/password",
     *      tags={"Company Account"},
     *      summary="Update company password",
     *      security={{"sanctum":{}}},
     *      @OA\Parameter(
     *          name="Accept",
     *          in="header",
     *          description="application/json",
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
    "current_password": "123456",
    "new_password": "123",
    "confirm_password": "123"
    }
     *          ),
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Successfully update company password",
     *          @OA\JsonContent(
     *              example=
    {
    "error": false,
    "message": "Xử lí thành công",
    "data": {
    "company_account": {
    "id": 1,
    "username": "ltd10",
    "is_verified": 0,
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
            $company_account = CompanyAccount::where('id', $request->user()->id)->first();

            if (!$company_account) {
                return $this->respondNotFound();
            }

            $current_password = $request->current_password;
            $new_password = $request->new_password;
            $salt_password = $current_password . env('PASSWORD_SALT');

            if (!Hash::check($salt_password, $company_account->password)) {
                return $this->respondBadRequest('Mật khẩu hiện tại không đúng');
            }

            $company_account->password = Hash::make($new_password . env('PASSWORD_SALT'));
            $company_account->save();

            return $this->respondWithData(
                [
                    'company_account' => $company_account
                ]);
        }
        catch (Exception $exception) {
            return $this->respondInternalServerError($exception->getMessage());
        }
    }

    /**
     *  @OA\Put(
     *      path="/api/company-accounts/verify/{id}",
     *      tags={"Company Account"},
     *      summary="Verify company account",
     *      security={{"sanctum":{}}},
     *      @OA\Parameter(
     *          name="id",
     *          in="path",
     *          description="Company account id",
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
     *          description="Successfully verify company account",
     *          @OA\JsonContent(
     *              example=
    {
    "error": false,
    "message": "Xử lí thành công",
    "data": {
    "company_account": {
    "id": 1,
    "username": "ltd10",
    "is_verified": 1,
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
     *          description="Company account not found",
     *          ref="#/components/responses/NotFound",
     *      ),
     *  )
     */
    public function verifyCompanyAccount(Request $request, string $id): JsonResponse
    {
        try {
            $company_account = CompanyAccount::where('id', $id)->first();

            if (!$company_account) {
                return $this->respondNotFound();
            }

            $company_account->is_verified = true;
            $company_account->save();

            return $this->respondWithData(
                [
                    'company_account' => $company_account
                ]);
        }
        catch (Exception $exception) {
            return $this->respondInternalServerError($exception->getMessage());
        }
    }

    /**
     *  @OA\Put(
     *      path="/api/company-accounts/ban/{id}",
     *      tags={"Company Account"},
     *      summary="Ban company account",
     *      security={{"sanctum":{}}},
     *      @OA\Parameter(
     *          name="id",
     *          in="path",
     *          description="Company account id",
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
     *          description="Successfully ban company account",
     *          @OA\JsonContent(
     *              example=
    {
    "error": false,
    "message": "Xử lí thành công",
    "data": {
    "companyAccount": {
    "id": 1,
    "username": "ltd10",
    "is_verified": 0,
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
     *          description="Company account not found",
     *          ref="#/components/responses/NotFound",
     *      ),
     *  )
     */
    public function banCompanyAccount(Request $request, string $id): JsonResponse
    {
        try {
            $company_account = CompanyAccount::where('id', $id)->first();

            if (!$company_account) {
                return $this->respondNotFound();
            }

            $company_account->is_banned = true;
            $company_account->save();

            $company_account->tokens()->delete();

            return $this->respondWithData(
                [
                    'company_account' => $company_account
                ]);
        }
        catch (Exception $exception) {
            return $this->respondInternalServerError($exception->getMessage());
        }
    }

    /**
     *  @OA\Put(
     *      path="/api/company-accounts/unban/{id}",
     *      tags={"Company Account"},
     *      summary="Unban company account",
     *      security={{"sanctum":{}}},
     *      @OA\Parameter(
     *          name="id",
     *          in="path",
     *          description="Company account id",
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
     *          description="Successfully unban company account",
     *          @OA\JsonContent(
     *              example=
    {
    "error": false,
    "message": "Xử lí thành công",
    "data": {
    "company_account": {
    "id": 1,
    "username": "ltd10",
    "is_verified": 0,
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
     *          description="Company account not found",
     *          ref="#/components/responses/NotFound",
     *      ),
     *  )
     */
    public function unbanCompanyAccount(Request $request, string $id): JsonResponse
    {
        try {
            $company_account = CompanyAccount::where('id', $id)->first();

            if (!$company_account) {
                return $this->respondNotFound();
            }

            $company_account->is_banned = false;
            $company_account->save();

            return $this->respondWithData(
                [
                    'company_account' => $company_account
                ]);
        }
        catch (Exception $exception) {
            return $this->respondInternalServerError($exception->getMessage());
        }
    }

    /**
     *  @OA\Put(
     *      path="/api/company-accounts/lock/{id}",
     *      tags={"Company Account"},
     *      summary="Lock company account",
     *      security={{"sanctum":{}}},
     *      @OA\Parameter(
     *          name="id",
     *          in="path",
     *          description="Company account id",
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
     *          description="Lock company account request body",
     *          @OA\JsonContent(
     *              example=
    {
    "locked_until": "2021-05-20 00:00:00"
    }
     *          ),
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Successfully lock company account",
     *          @OA\JsonContent(
     *              example=
    {
    "error": false,
    "message": "Xử lí thành công",
    "data": {
    "company_account": {
    "id": 1,
    "username": "ltd10",
    "is_verified": 0,
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
     *          description="Company account not found",
     *          ref="#/components/responses/NotFound",
     *      ),
     *  )
     */
    public function lockCompanyAccount(Request $request, string $id): JsonResponse
    {
        try {
            $company_account = CompanyAccount::where('id', $id)->first();

            if (!$company_account) {
                return $this->respondNotFound();
            }

            $company_account->locked_until = $request->locked_until;
            $company_account->save();

            $company_account->tokens()->delete();

            return $this->respondWithData(
                [
                    'company_account' => $company_account
                ]);
        }
        catch (Exception $exception) {
            return $this->respondInternalServerError($exception->getMessage());
        }
    }

    /**
     *  @OA\Put(
     *      path="/api/company-accounts/unlock/{id}",
     *      tags={"Company Account"},
     *      summary="Unlock company account",
     *      security={{"sanctum":{}}},
     *      @OA\Parameter(
     *          name="id",
     *          in="path",
     *          description="Company account id",
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
     *          description="Successfully unlock company account",
     *          @OA\JsonContent(
     *              example=
    {
    "error": false,
    "message": "Xử lí thành công",
    "data": {
    "company_account": {
    "id": 1,
    "username": "ltd10",
    "is_verified": 0,
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
     *          description="Company account not found",
     *          ref="#/components/responses/NotFound",
     *      ),
     *  )
     */
    public function unlockCompanyAccount(Request $request, string $id): JsonResponse
    {
        try {
            $company_account = CompanyAccount::where('id', $id)->first();

            if (!$company_account) {
                return $this->respondNotFound();
            }

            $company_account->locked_until = null;
            $company_account->save();

            return $this->respondWithData(
                [
                    'company_account' => $company_account
                ]);
        }
        catch (Exception $exception) {
            return $this->respondInternalServerError($exception->getMessage());
        }
    }

    /**
     *  @OA\Delete(
     *      path="/api/company-accounts/{id}",
     *      tags={"Company Account"},
     *      summary="Delete company account",
     *      security={{"sanctum":{}}},
     *      @OA\Parameter(
     *          name="id",
     *          in="path",
     *          description="Company account id",
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
     *          description="Successfully delete company account",
     *          @OA\JsonContent(
     *              example=
    {
    "error": false,
    "message": "Xoá thành công",
    "data": {
    "company_account": {
    "id": 20,
    "username": "dulich",
    "is_verified": 0,
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
     *          description="Company account not found",
     *          ref="#/components/responses/NotFound",
     *      ),
     *  )
     */
    public function deleteCompanyAccount(Request $request, string $id): JsonResponse
    {
        try {
            $company_account = CompanyAccount::where('id', $id)->first();
            $profile = CompanyProfile::where('id', $id)->first();

            if (!$company_account || !$profile) {
                return $this->respondNotFound();
            }

            $company_account->delete();
            $profile->delete();

            return $this->respondWithData(
                [
                    'company_account' => $company_account
                ], 'Xoá thành công');
        }
        catch (Exception $exception) {
            return $this->respondInternalServerError($exception->getMessage());
        }
    }
}
