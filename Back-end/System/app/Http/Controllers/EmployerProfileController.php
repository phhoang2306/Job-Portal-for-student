<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdateEmployerProfileRequest;
use App\Models\EmployerProfile;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class EmployerProfileController extends ApiController
{
    /**
     *  @OA\Get(
     *      path="/employer-profiles",
     *      tags={"Employer Profiles"},
     *      summary="Get all employer profiles",
     *      security={{"sanctum":{}}},
     *      @OA\Parameter(
     *          name="count_per_page",
     *          description="Number of employer profiles per page",
     *          in="query",
     *      ),
     *      @OA\Parameter(
     *          name="order_by",
     *          description="Order by column",
     *          in="query",
     *      ),
     *      @OA\Parameter(
     *          name="order_type",
     *          description="Order type (asc or desc)",
     *          in="query",
     *      ),
     *      @OA\Parameter(
     *          name="company_id",
     *          description="Filter by company id",
     *          in="query",
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Successfully retrieved employer profiles",
     *          @OA\JsonContent(
     *              example=
    {
    "error": false,
    "message": "Successfully retrieved employer profiles",
    "data": {
    "employer_profiles": {
    "current_page": 1,
    "data": {
    {
    "id": 1,
    "company_id": 20,
    "full_name": "Nguyen Van A",
    "avatar": "https://i.imgur.com/hepj9ZS.png"
    },
    {
    "id": 2,
    "company_id": 5,
    "full_name": "Nguyen Khanh Hoang",
    "avatar": "https://i.imgur.com/hepj9ZS.png"
    }
    },
    "first_page_url": "http://localhost:8000/api/employer-profiles?page=1",
    "from": 1,
    "last_page": 20,
    "last_page_url": "http://localhost:8000/api/employer-profiles?page=20",
    "links": {
    {
    "url": null,
    "label": "&laquo; Previous",
    "active": false
    },
    {
    "url": "http://localhost:8000/api/employer-profiles?page=1",
    "label": "1",
    "active": true
    },
    {
    "url": "http://localhost:8000/api/employer-profiles?page=2",
    "label": "2",
    "active": false
    },
    {
    "url": "http://localhost:8000/api/employer-profiles?page=3",
    "label": "3",
    "active": false
    },
    {
    "url": "http://localhost:8000/api/employer-profiles?page=4",
    "label": "4",
    "active": false
    },
    {
    "url": "http://localhost:8000/api/employer-profiles?page=5",
    "label": "5",
    "active": false
    },
    {
    "url": "http://localhost:8000/api/employer-profiles?page=6",
    "label": "6",
    "active": false
    },
    {
    "url": "http://localhost:8000/api/employer-profiles?page=7",
    "label": "7",
    "active": false
    },
    {
    "url": "http://localhost:8000/api/employer-profiles?page=8",
    "label": "8",
    "active": false
    },
    {
    "url": "http://localhost:8000/api/employer-profiles?page=9",
    "label": "9",
    "active": false
    },
    {
    "url": "http://localhost:8000/api/employer-profiles?page=10",
    "label": "10",
    "active": false
    },
    {
    "url": null,
    "label": "...",
    "active": false
    },
    {
    "url": "http://localhost:8000/api/employer-profiles?page=19",
    "label": "19",
    "active": false
    },
    {
    "url": "http://localhost:8000/api/employer-profiles?page=20",
    "label": "20",
    "active": false
    },
    {
    "url": "http://localhost:8000/api/employer-profiles?page=2",
    "label": "Next &raquo;",
    "active": false
    }
    },
    "next_page_url": "http://localhost:8000/api/employer-profiles?page=2",
    "path": "http://localhost:8000/api/employer-profiles",
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
     *          description="No employers found",
     *          ref="#/components/responses/NotFound"
     *      )
     *  )
     */
    public function getEmployerProfiles(Request $request): JsonResponse
    {
        try {
            $count_per_page = $request->count_per_page ?? 10;
            $order_by = $request->order_by ?? 'id';
            $order_type = $request->order_type ?? 'asc';

            $employer_profiles = EmployerProfile::filter($request, EmployerProfile::query())
                ->orderBy($order_by, $order_type)
                ->paginate($count_per_page);

            if (count($employer_profiles) === 0) {
                return $this->respondNotFound('No employers found');
            }

            return $this->respondWithData(
                [
                    'employer_profiles' => $employer_profiles,
                ]
                , 'Successfully retrieved employer profiles');
        }
        catch (Exception $exception) {
            return $this->respondInternalServerError($exception->getMessage());
        }
    }

    /**
     * @OA\Get(
     *      path="/employer-profiles/{id}",
     *      tags={"Employer Profiles"},
     *      summary="Get employer profile information",
     *      security={{"sanctum":{}}},
     *      @OA\Parameter(
     *          name="id",
     *          description="Employer profile id",
     *          required=true,
     *          in="path"
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Successfully retrieved employer profile",
     *          @OA\JsonContent(
     *              example=
    {
    "error": false,
    "message": "Successfully retrieved employer profile",
    "data": {
    "employer_profile": {
    "id": 1,
    "company_id": 20,
    "full_name": "Nguyen Van A",
    "avatar": "https://i.imgur.com/hepj9ZS.png"
    }
    },
    "status_code": 200
    }
     *          )
     *      ),
     *      @OA\Response(
     *          response=404,
     *          description="Employer profile not found",
     *          ref="#/components/responses/NotFound"
     *      )
     *  )
     */
    public function getEmployerProfileById(Request $request, string $id): JsonResponse
    {
        try {
            $employer_profile = EmployerProfile::where('id', $id)->first();

            if (!$employer_profile) {
                return $this->respondNotFound('Employer profile not found');
            }

            return $this->respondWithData(
                [
                    'employer_profile' => $employer_profile,
                ]
                , 'Successfully retrieved employer profile');
        }
        catch (Exception $exception) {
            return $this->respondInternalServerError($exception->getMessage());
        }
    }

    /**
     * @OA\Put(
     *      path="/employer-profiles/{id}",
     *      tags={"Employer Profiles"},
     *      summary="Update employer profile information",
     *      security={{"sanctum":{}}},
     *      @OA\Parameter(
     *          name="id",
     *          description="Employer profile id",
     *          required=true,
     *          in="path"
     *      ),
     *      @OA\RequestBody(
     *          required=true,
     *          @OA\JsonContent(
     *              example=
    {
    "full_name": "Nguyen Van B",
    }
     *          )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Successfully updated employer profile",
     *          @OA\JsonContent(
     *              example=
    {
    "error": false,
    "message": "Xử lí thành công",
    "data": {
    "employer_profile": {
    "id": 1,
    "company_id": 20,
    "full_name": "Nguyen Van B",
    "avatar": "https://i.imgur.com/hepj9ZS.png"
    }
    },
    "status_code": 200
    }
     *          )
     *      ),
     *      @OA\Response(
     *          response=404,
     *          description="Employer profile not found",
     *          ref="#/components/responses/NotFound"
     *      )
     *  )
     */
    public function updateEmployerProfile(UpdateEmployerProfileRequest $request, string $id): JsonResponse
    {
        try {
            $employer_profile = EmployerProfile::where('id', $id)->first();

            if (!$employer_profile) {
                return $this->respondNotFound();
            }

            if ($request->user()->id !== $employer_profile->id) {
                return $this->respondForbidden('Bạn không có quyền chỉnh sửa thông tin này');
            }

            $employer_profile->full_name = $request->full_name ?? $employer_profile->full_name;

            if ($request->hasFile('avatar')) {
                $file = $request->file('avatar');
                $file_name = $file->getClientOriginalName();
                $file_name = str_replace(' ', '_', $file_name);
                $file_name = preg_replace('/[^A-Za-z0-9\-\.]/', '', $file_name);
                $file_name = time() . '_' . $file_name;

                $path = Storage::disk('s3')->putFileAs(
                    'employer_avatar',
                    $file,
                    $file_name,
                );
                $url = Storage::disk('s3')->url($path);

                if (!$path || !$url) {
                    return $this->respondInternalServerError('Lỗi khi upload ảnh');
                }

                $employer_profile->avatar = $url;
            }

            $employer_profile->save();

            return $this->respondWithData(
                [
                    'employer_profile' => $employer_profile,
                ]);
        }
        catch (Exception $exception) {
            return $this->respondInternalServerError($exception->getMessage());
        }
    }

    public function updateEmployerAvatar(Request $request, string $id): JsonResponse
    {
        try {
            $employer_profile = EmployerProfile::where('id', $id)->first();

            if (!$employer_profile) {
                return $this->respondNotFound();
            }

            if ($request->user()->id !== $employer_profile->id) {
                return $this->respondForbidden('Bạn không có quyền chỉnh sửa thông tin này');
            }

            if ($request->hasFile('avatar')) {
                $file = $request->file('avatar');
                $file_name = $file->getClientOriginalName();
                $file_name = str_replace(' ', '_', $file_name);
                $file_name = preg_replace('/[^A-Za-z0-9\-\.]/', '', $file_name);
                $file_name = time() . '_' . $file_name;

                $path = Storage::disk('s3')->putFileAs(
                    'employer_avatar',
                    $file,
                    $file_name,
                );
                $url = Storage::disk('s3')->url($path);

                if (!$path || !$url) {
                    return $this->respondInternalServerError('Lỗi khi upload ảnh');
                }

                $employer_profile->avatar = $url;
                $employer_profile->save();
            }

            return $this->respondWithData(
                [
                    'employer_profile' => $employer_profile,
                ]);
        }
        catch (Exception $exception) {
            return $this->respondInternalServerError($exception->getMessage());
        }
    }
}
