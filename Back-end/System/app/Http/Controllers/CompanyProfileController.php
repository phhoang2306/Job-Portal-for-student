<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdateCompanyProfileRequest;
use App\Models\CompanyProfile;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class CompanyProfileController extends ApiController
{
    /**
     *  @OA\Get(
     *      path="/api/company-profiles",
     *      tags={"Company Profiles"},
     *      summary="Get all company profiles",
     *      @OA\Parameter(
     *          name="count_per_page",
     *          in="query",
     *          description="Number of company profiles per page",
     *          required=false,
     *      ),
     *      @OA\Parameter(
     *          name="Accept",
     *          in="header",
     *          description="Accept",
     *          required=false,
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Successfully retrieved company profiles",
     *          @OA\JsonContent(
     *              example=
    {
    "error": false,
    "message": "Xử lí thành công",
    "data": {
    "company_profiles": {
    "current_page": 1,
    "data": {
    {
    "id": 1,
    "name": "TV TPI CO., LTD",
    "logo": "https://i.imgur.com/hepj9ZS.png",
    "description": "['TV TPI là tổ chức luôn mang sứ mệnh cung cấp cho người sử dụng thuốc các sản phẩm giá trị và chất lượng được đăng ký tại Châu Âu với giá thành hợp lý. Bên cạnh sự phát triển kinh doanh, chúng tôi luôn ý thức được rằng mỗi nhân sự là một mắt xích quan trọng, là nhân tài của tổ chức. Do đó, TV TPI luôn tìm kiếm những con người mong muốn phát triển bản thân, không ngừng học hỏi và góp phần tạo nên sự phát triển bền vững của công ty.', 'Sứ mệnh', 'TV TPI ra đời nhằm phụng sự cho người sử dụng thuốc tại Việt Nam và các nước Đông Nam Á qua việc cung cấp cho người sử dụng thuốc các sản phẩm giá trị và chất lượng được đăng ký tại Châu Âu với giá thành hợp lý.', 'Tầm nhìn', 'Đưa TV TPI nằm trong Top 1.000 công ty Dược vào năm 2027. Giữ vững là Công ty số 1 cung cấp các dịch vụ EU GMP cho tất cả các đối tác hoạt động tại Việt Nam. Là Công ty đầu tiên tại Việt Nam sở hữu số đăng ký thuốc “Generics” được sản xuất tại Việt Nam nhiều nhất tại Châu Âu.', 'Công Ty hoạt động trong lĩnh vực Kinh Doanh và Phân Phối các sản phẩm:']",
    "site": "http://tvtpi.com.vn/",
    "address": "72 Bình Giã, Phường 13, Quận Tân Bình, TP. HCM",
    "size": "25-99"
    }
    },
    "first_page_url": "http://localhost:8000/api/company-profiles?page=1",
    "from": 1,
    "last_page": 20,
    "last_page_url": "http://localhost:8000/api/company-profiles?page=20",
    "links": {
    {
    "url": null,
    "label": "&laquo; Previous",
    "active": false
    },
    {
    "url": "http://localhost:8000/api/company-profiles?page=1",
    "label": "1",
    "active": true
    },
    {
    "url": "http://localhost:8000/api/company-profiles?page=2",
    "label": "2",
    "active": false
    },
    {
    "url": "http://localhost:8000/api/company-profiles?page=3",
    "label": "3",
    "active": false
    },
    {
    "url": "http://localhost:8000/api/company-profiles?page=4",
    "label": "4",
    "active": false
    },
    {
    "url": "http://localhost:8000/api/company-profiles?page=5",
    "label": "5",
    "active": false
    },
    {
    "url": "http://localhost:8000/api/company-profiles?page=6",
    "label": "6",
    "active": false
    },
    {
    "url": "http://localhost:8000/api/company-profiles?page=7",
    "label": "7",
    "active": false
    },
    {
    "url": "http://localhost:8000/api/company-profiles?page=8",
    "label": "8",
    "active": false
    },
    {
    "url": "http://localhost:8000/api/company-profiles?page=9",
    "label": "9",
    "active": false
    },
    {
    "url": "http://localhost:8000/api/company-profiles?page=10",
    "label": "10",
    "active": false
    },
    {
    "url": null,
    "label": "...",
    "active": false
    },
    {
    "url": "http://localhost:8000/api/company-profiles?page=19",
    "label": "19",
    "active": false
    },
    {
    "url": "http://localhost:8000/api/company-profiles?page=20",
    "label": "20",
    "active": false
    },
    {
    "url": "http://localhost:8000/api/company-profiles?page=2",
    "label": "Next &raquo;",
    "active": false
    }
    },
    "next_page_url": "http://localhost:8000/api/company-profiles?page=2",
    "path": "http://localhost:8000/api/company-profiles",
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
     *          description="No company profiles found",
     *          ref="#/components/responses/NotFound",
     *      ),
     *  )
     */
    public function getAllCompanyProfiles(Request $request): JsonResponse
    {
        try {
            $count_per_page = $request->count_per_page ?? 10;
            $order_by = $request->order_by ?? 'id';
            $order_type = $request->order_direction ?? 'asc';

            $company_profiles = CompanyProfile::filter($request, CompanyProfile::query())
                ->orderBy($order_by, $order_type)
                ->paginate($count_per_page);

            if (count($company_profiles) === 0) {
                return $this->respondNotFound();
            }

            return $this->respondWithData(
                [
                    'company_profiles' => $company_profiles,
                ]);
        }
        catch (Exception $exception) {
            return $this->respondInternalServerError($exception->getMessage());
        }
    }

    /**
     *  @OA\Get(
     *      path="/api/company-profiles/{id}",
     *      tags={"Company Profiles"},
     *      summary="Get company profile information",
     *      @OA\Parameter(
     *          name="id",
     *          in="path",
     *          description="Company profile id",
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
     *          description="Company profile information",
     *          @OA\JsonContent(
     *              example=
    {
    "error": false,
    "message": "Xử lí thành công",
    "data": {
    "company_profile": {
    "current_page": 1,
    "data": {
    {
    "id": 1,
    "name": "TV TPI CO., LTD",
    "logo": "https://i.imgur.com/hepj9ZS.png",
    "description": "['TV TPI là tổ chức luôn mang sứ mệnh cung cấp cho người sử dụng thuốc các sản phẩm giá trị và chất lượng được đăng ký tại Châu Âu với giá thành hợp lý. Bên cạnh sự phát triển kinh doanh, chúng tôi luôn ý thức được rằng mỗi nhân sự là một mắt xích quan trọng, là nhân tài của tổ chức. Do đó, TV TPI luôn tìm kiếm những con người mong muốn phát triển bản thân, không ngừng học hỏi và góp phần tạo nên sự phát triển bền vững của công ty.', 'Sứ mệnh', 'TV TPI ra đời nhằm phụng sự cho người sử dụng thuốc tại Việt Nam và các nước Đông Nam Á qua việc cung cấp cho người sử dụng thuốc các sản phẩm giá trị và chất lượng được đăng ký tại Châu Âu với giá thành hợp lý.', 'Tầm nhìn', 'Đưa TV TPI nằm trong Top 1.000 công ty Dược vào năm 2027. Giữ vững là Công ty số 1 cung cấp các dịch vụ EU GMP cho tất cả các đối tác hoạt động tại Việt Nam. Là Công ty đầu tiên tại Việt Nam sở hữu số đăng ký thuốc “Generics” được sản xuất tại Việt Nam nhiều nhất tại Châu Âu.', 'Công Ty hoạt động trong lĩnh vực Kinh Doanh và Phân Phối các sản phẩm:']",
    "site": "http://tvtpi.com.vn/",
    "address": "72 Bình Giã, Phường 13, Quận Tân Bình, TP. HCM",
    "size": "25-99"
    }
    },
    "first_page_url": "http://localhost:8000/api/company-profiles/1?page=1",
    "from": 1,
    "last_page": 1,
    "last_page_url": "http://localhost:8000/api/company-profiles/1?page=1",
    "links": {
    {
    "url": null,
    "label": "&laquo; Previous",
    "active": false
    },
    {
    "url": "http://localhost:8000/api/company-profiles/1?page=1",
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
    "path": "http://localhost:8000/api/company-profiles/1",
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
     *          description="Company profile not found",
     *          ref="#/components/responses/NotFound",
     *      ),
     *  )
     */
    public function getCompanyProfileById(Request $request, string $id): JsonResponse
    {
        try {
            $company_profile = CompanyProfile::where('id', $id)->first();

            if (!$company_profile) {
                return $this->respondNotFound();
            }

            return $this->respondWithData(
                [
                    'company_profile' => $company_profile,
                ]);
        }
        catch (Exception $exception) {
            return $this->respondInternalServerError($exception->getMessage());
        }
    }

    /**
     *  @OA\Put(
     *      path="/api/company-profiles/{id}",
     *      tags={"Company Profiles"},
     *      summary="Update company profile",
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
     *          description="Company profile information",
     *          @OA\JsonContent(
     *              example=
    {
    "size": 22
    }
     *          ),
     *      ),
     *      @OA\Response(
     *          response=201,
     *          description="Company profile created",
     *          @OA\JsonContent(
     *              example=
    {
    "error": false,
    "message": "Xử lí thành công",
    "data": {
    "company_profile": {
    "id": 1,
    "name": "TV TPI CO., LTD",
    "logo": "https://i.imgur.com/hepj9ZS.png",
    "description": "['TV TPI là tổ chức luôn mang sứ mệnh cung cấp cho người sử dụng thuốc các sản phẩm giá trị và chất lượng được đăng ký tại Châu Âu với giá thành hợp lý. Bên cạnh sự phát triển kinh doanh, chúng tôi luôn ý thức được rằng mỗi nhân sự là một mắt xích quan trọng, là nhân tài của tổ chức. Do đó, TV TPI luôn tìm kiếm những con người mong muốn phát triển bản thân, không ngừng học hỏi và góp phần tạo nên sự phát triển bền vững của công ty.', 'Sứ mệnh', 'TV TPI ra đời nhằm phụng sự cho người sử dụng thuốc tại Việt Nam và các nước Đông Nam Á qua việc cung cấp cho người sử dụng thuốc các sản phẩm giá trị và chất lượng được đăng ký tại Châu Âu với giá thành hợp lý.', 'Tầm nhìn', 'Đưa TV TPI nằm trong Top 1.000 công ty Dược vào năm 2027. Giữ vững là Công ty số 1 cung cấp các dịch vụ EU GMP cho tất cả các đối tác hoạt động tại Việt Nam. Là Công ty đầu tiên tại Việt Nam sở hữu số đăng ký thuốc “Generics” được sản xuất tại Việt Nam nhiều nhất tại Châu Âu.', 'Công Ty hoạt động trong lĩnh vực Kinh Doanh và Phân Phối các sản phẩm:']",
    "site": "http://tvtpi.com.vn/",
    "address": "72 Bình Giã, Phường 13, Quận Tân Bình, TP. HCM",
    "size": "22"
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
    public function updateCompanyProfile(UpdateCompanyProfileRequest $request): JsonResponse
    {
        try {
            $company_profile = CompanyProfile::where('id', $request->user()->id)->first();

            if (!$company_profile) {
                return $this->respondNotFound();
            }

            if ($request->user()->id !== $company_profile->id) {
                return $this->respondForbidden('Bạn không có quyền chỉnh sửa thông tin này');
            }

            $company_profile->name = $request->name ?? $company_profile->name;
            $company_profile->description = $request->description ?? $company_profile->description;
            $company_profile->site = $request->site ?? $company_profile->site;
            $company_profile->address = $request->address ?? $company_profile->address;
            $company_profile->size = $request->size ?? $company_profile->size;
            $company_profile->phone = $request->phone ?? $company_profile->phone;
            $company_profile->email = $request->email ?? $company_profile->email;

            if ($request->hasFile('logo')) {
                $file = $request->file('logo');
                $file_name = $file->getClientOriginalName();
                $file_name = str_replace(' ', '_', $file_name);
                $file_name = preg_replace('/[^A-Za-z0-9\-\.]/', '', $file_name);
                $file_name = time() . '_' . $file_name;

                $path = Storage::disk('s3')->putFileAs(
                    'company_logo',
                    $file,
                    $file_name,
                );
                $url = Storage::disk('s3')->url($path);

                if (!$path || !$url) {
                    return $this->respondInternalServerError('Lỗi khi upload logo');
                }

                $company_profile->logo = $url;
            }

            $company_profile->save();

            return $this->respondWithData(
                [
                    'company_profile' => $company_profile,
                ]);
        }
        catch (Exception $exception) {
            return $this->respondInternalServerError($exception->getMessage());
        }
    }

    public function updateCompanyLogo(Request $request, string $id): JsonResponse
    {
        try {
            $company_profile = CompanyProfile::where('id', $id)->first();

            if (!$company_profile) {
                return $this->respondNotFound();
            }

            // upload logo
            if ($request->hasFile('logo')) {
                $file = $request->file('logo');
                $file_name = $file->getClientOriginalName();
                $file_name = str_replace(' ', '_', $file_name);
                $file_name = preg_replace('/[^A-Za-z0-9\-\.]/', '', $file_name);
                $file_name = time() . '_' . $file_name;

                $path = Storage::disk('s3')->putFileAs(
                    'company_logo',
                    $file,
                    $file_name,
                );
                $url = Storage::disk('s3')->url($path);

                if (!$path || !$url) {
                    return $this->respondInternalServerError('Không thể upload logo');
                }

                $company_profile->logo = $url;
                $company_profile->save();
            }

            return $this->respondWithData(
                [
                    'company_profile' => $company_profile,
                ], 'Cập nhật logo thành công'
            );
        }
        catch (Exception $exception) {
            return $this->respondInternalServerError($exception->getMessage());
        }
    }
}
