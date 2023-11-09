<?php

namespace App\Http\Controllers;

use App\Models\CompanyVerification;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CompanyVerificationController extends ApiController
{
    /**
     *  @OA\Get(
     *      path="/company-verifications",
     *      summary="Company Verifications",
     *      tags={"Company Verifications"},
     *      security={{"sanctum":{}}},
     *      @OA\Parameter(
     *          name="company_id",
     *          in="query",
     *          description="company id",
     *          required=false,
     *      ),
     *      @OA\Parameter(
     *          name="status",
     *          in="query",
     *          description="Verification status (0, 1, 2)",
     *          required=false
     *      ),
     *      @OA\Parameter(
     *          name="order_by",
     *          in="query",
     *          description="Order by field",
     *          required=false,
     *      ),
     *      @OA\Parameter(
     *          name="order_type",
     *          in="query",
     *          description="Order type (asc, desc)",
     *          required=false,
     *      ),
     *      @OA\Parameter(
     *          name="count_per_page",
     *          in="query",
     *          description="Number of records per page",
     *          required=false
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Success",
     *          @OA\JsonContent(
     *              example=
    {}
     *          )
     *      ),
     *      @OA\Response(
     *          response=404,
     *          description="Not Found",
     *          ref="#/components/responses/NotFound"
     *      )
     *  )
     */
    public function getCompanyVerifications(Request $request): JsonResponse
    {
        try {
            $count_per_page = $request->count_per_page ?? 10;
            $order_by = $request->order_by ?? 'id';
            $order_type = $request->order_type ?? 'asc';

            $company_verifications = CompanyVerification::filter($request, CompanyVerification::query())
                ->orderBy($order_by, $order_type)
                ->paginate($count_per_page);

            if (count($company_verifications) === 0) {
                return $this->respondNotFound();
            }

            return $this->respondWithData(
                [
                    'company_verifications' => $company_verifications,
                ]);
        }
        catch (Exception $exception) {
            return $this->respondInternalServerError($exception->getMessage());
        }
    }

    /**
     *  @OA\Get(
     *      path="/company-verifications/{id}",
     *      summary="Company Verification by id",
     *      tags={"Company Verifications"},
     *      security={{"sanctum":{}}},
     *      @OA\Parameter(
     *          name="id",
     *          in="path",
     *          description="Company Verification id",
     *          required=true,
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Success",
     *          @OA\JsonContent(
     *              example=
    {}
     *          )
     *      ),
     *      @OA\Response(
     *          response=404,
     *          description="Not Found",
     *          ref="#/components/responses/NotFound"
     *      )
     *  )
     */
    public function getCompanyVerificationById(Request $request, string $id): JsonResponse
    {
        try {
            $company_verification = CompanyVerification::where('id', $id)->first();

            if (!$company_verification) {
                return $this->respondNotFound();
            }

            return $this->respondWithData(
                [
                    'company_verification' => $company_verification,
                ]);
        }
        catch (Exception $exception) {
            return $this->respondInternalServerError($exception->getMessage());
        }
    }

    /**
     *  @OA\Post(
     *      path="/company-verifications",
     *      summary="Create Company Verification",
     *      tags={"Company Verifications"},
     *      security={{"sanctum":{}}},
     *      @OA\RequestBody(
     *          required=true,
     *          @OA\JsonContent(
     *              required={"verification_url"},
     *              example=
    {
    "verification_url": "https://www.google.com"
    }
     *          )
     *      ),
     *      @OA\Response(
     *          response=201,
     *          description="Created",
     *          @OA\JsonContent(
     *              example=
    {
    "error": false,
    "message": "Tạo thành công",
    "data": {
    "company_verification": {
    "company_id": 1,
    "verification_url": "google.com",
    "id": 1
    }
    },
    "status_code": 201
    }
     *          )
     *      ),
     *      @OA\Response(
     *          response=400,
     *          description="Bad Request",
     *          ref="#/components/responses/BadRequest"
     *      ),
     *      @OA\Response(
     *          response=500,
     *          description="Internal Server Error",
     *          ref="#/components/responses/InternalServerError"
     *      )
     *  )
     */
    public function createCompanyVerification(Request $request): JsonResponse
    {
        try {
            $company_verification = new CompanyVerification();
            $company_verification->company_id = $request->user()->id;
            $company_verification->verification_url = $request->verification_url;
            $company_verification->save();

            return $this->respondCreated(
                [
                    'company_verification' => $company_verification,
                ]);
        }
        catch (Exception $exception) {
            return $this->respondInternalServerError($exception->getMessage());
        }
    }

    /**
     *  @OA\Put(
     *      path="/company-verifications/approve/{id}",
     *      summary="Approve Company Verification",
     *      tags={"Company Verifications"},
     *      security={{"sanctum":{}}},
     *      @OA\Parameter(
     *          name="id",
     *          in="path",
     *          description="Company Verification id",
     *          required=true,
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Success",
     *          @OA\JsonContent(
     *              example=
    {
    "error": false,
    "message": "Xử lí thành công",
    "data": {
    "company_verification": {
    "id": 1,
    "company_id": 1,
    "verification_url": "google.com",
    "status": "Hợp lệ"
    }
    },
    "status_code": 200
    }
     *          )
     *      ),
     *      @OA\Response(
     *          response=404,
     *          description="Not Found",
     *          ref="#/components/responses/NotFound"
     *      )
     *  )
     */
    public function approveCompanyVerification(Request $request, string $id): JsonResponse
    {
        try {
            $company_verification = CompanyVerification::where('id', $id)->first();

            if (!$company_verification) {
                return $this->respondNotFound();
            }

            $company_verification->status = 'Hợp lệ';
            $company_verification->save();

            return $this->respondWithData(
                [
                    'company_verification' => $company_verification,
                ]);
        }
        catch (Exception $exception) {
            return $this->respondInternalServerError($exception->getMessage());
        }
    }

    /**
     *  @OA\Put(
     *      path="/company-verifications/reject/{id}",
     *      summary="Reject Company Verification",
     *      tags={"Company Verifications"},
     *      security={{"sanctum":{}}},
     *      @OA\Parameter(
     *          name="id",
     *          in="path",
     *          description="Company Verification id",
     *          required=true,
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Success",
     *          @OA\JsonContent(
     *              example=
    {
    "error": false,
    "message": "Xử lí thành công",
    "data": {
    "company_verification": {
    "id": 1,
    "company_id": 1,
    "verification_url": "google.com",
    "status": "Không hợp lệ"
    }
    },
    "status_code": 200
    }
     *          )
     *      ),
     *      @OA\Response(
     *          response=404,
     *          description="Not Found",
     *          ref="#/components/responses/NotFound"
     *      )
     *  )
     */
    public function rejectCompanyVerification(Request $request, string $id): JsonResponse
    {
        try {
            $company_verification = CompanyVerification::where('id', $id)->first();

            if (!$company_verification) {
                return $this->respondNotFound();
            }

            $company_verification->status = 'Không hợp lệ';
            $company_verification->save();

            return $this->respondWithData(
                [
                    'company_verification' => $company_verification,
                ]);
        }
        catch (Exception $exception) {
            return $this->respondInternalServerError($exception->getMessage());
        }
    }

    /**
     *  @OA\Delete(
     *      path="/company-verifications/{id}",
     *      summary="Delete Company Verification",
     *      tags={"Company Verifications"},
     *      security={{"sanctum":{}}},
     *      @OA\Parameter(
     *          name="id",
     *          in="path",
     *          description="Company Verification id",
     *          required=true,
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Success",
     *          @OA\JsonContent(
     *              example=
    {
    "error": false,
    "message": "Xóa thành công",
    "data": {
    "company_verification": {
    "id": 1,
    "company_id": 1,
    "verification_url": "google.com",
    "status": "Hợp lệ"
    }
    },
    "status_code": 200
    }
     *          )
     *      ),
     *      @OA\Response(
     *          response=404,
     *          description="Not Found",
     *          ref="#/components/responses/NotFound"
     *      )
     *  )
     */
    public function deleteCompanyVerification(Request $request, string $id): JsonResponse
    {
        try {
            $company_verification = CompanyVerification::where('id', $id)->first();

            if (!$company_verification) {
                return $this->respondNotFound();
            }

            $company_verification->delete();

            return $this->respondWithData(
                [
                    'company_verification' => $company_verification,
                ], 'Xóa thành công');
        }
        catch (Exception $exception) {
            return $this->respondInternalServerError($exception->getMessage());
        }
    }
}
