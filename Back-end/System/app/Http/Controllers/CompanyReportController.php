<?php

namespace App\Http\Controllers;

use App\Models\CompanyReport;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CompanyReportController extends ApiController
{
    /**
     *  @OA\Get(
     *      path="/company-reports",
     *      summary="Get company reports",
     *      tags={"Company Report"},
     *      security={{"sanctum":{}}},
     *      @OA\Parameter(
     *          name="company_id",
     *          in="query",
     *          description="Filter by company id",
     *          @OA\Schema(
     *              type="integer",
     *              example=1
     *          )
     *      ),
     *      @OA\Parameter(
     *          name="user_id",
     *          in="query",
     *          description="Filter by user id",
     *          @OA\Schema(
     *              type="integer",
     *              example=1
     *          )
     *      ),
     *      @OA\Parameter(
     *          name="order_by",
     *          in="query",
     *          description="Order by field",
     *          @OA\Schema(
     *              type="string",
     *              example="id"
     *          )
     *      ),
     *      @OA\Parameter(
     *          name="order_type",
     *          in="query",
     *          description="Order type (asc/desc)",
     *          @OA\Schema(
     *              type="string",
     *              example="desc"
     *          )
     *      ),
     *      @OA\Parameter(
     *          name="count_per_page",
     *          in="query",
     *          description="Count per page",
     *          @OA\Schema(
     *              type="integer",
     *              example=10
     *          )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Company reports",
     *          @OA\JsonContent(
     *              example=
    {
    "error": false,
    "message": "Xử lí thành công",
    "data": {
    "company_reports": {
    "current_page": 1,
    "data": {
    {
    "id": 1,
    "company_id": 1,
    "user_id": 1,
    "reason": "Thiếu mức lương"
    },
    {
    "id": 2,
    "company_id": 1,
    "user_id": 2,
    "reason": "Thiếu mức lương"
    },
    {
    "id": 3,
    "company_id": 2,
    "user_id": 3,
    "reason": "Cần thêm thông tin về công ty như con đường sứ mệnh, hỗ trợ từ công ty"
    }
    },
    "first_page_url": "http://127.0.0.1:8000/api/company-reports?page=1",
    "from": 1,
    "last_page": 1,
    "last_page_url": "http://127.0.0.1:8000/api/company-reports?page=1",
    "links": {
    {
    "url": null,
    "label": "&laquo; Previous",
    "active": false
    },
    {
    "url": "http://127.0.0.1:8000/api/company-reports?page=1",
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
    "path": "http://127.0.0.1:8000/api/company-reports",
    "per_page": 10,
    "prev_page_url": null,
    "to": 3,
    "total": 3
    }
    },
    "status_code": 200
    }
     *          )
     *      ),
     *      @OA\Response(
     *          response=404,
     *          description="Company reports not found",
     *          ref="#/components/responses/NotFound"
     *      )
     *  )
     */
    public function getCompanyReports(Request $request): JsonResponse
    {
        try {
            $count_per_page = $request->count_per_page ?? 10;
            $order_by = $request->order_by ?? 'id';
            $order_type = $request->order_type ?? 'asc';

            $company_reports = CompanyReport::filter($request, CompanyReport::query())
                ->with('company_profile', 'user_profile', 'user')
                ->orderBy($order_by, $order_type)
                ->paginate($count_per_page);

            if (count($company_reports) === 0) {
                return $this->respondNotFound();
            }

            return $this->respondWithData(
                [
                    'company_reports' => $company_reports,
                ]);
        }
        catch (Exception $exception) {
            return $this->respondInternalServerError($exception->getMessage());
        }
    }

    /**
     *  @OA\Get(
     *      path="/company-reports/{id}",
     *      summary="Get company report by id",
     *      tags={"Company Report"},
     *      security={{"sanctum":{}}},
     *      @OA\Parameter(
     *          name="id",
     *          in="path",
     *          description="Company report id",
     *          required=true,
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Company report",
     *          @OA\JsonContent(
     *              example=
    {
    "error": false,
    "message": "Xử lí thành công",
    "data": {
    "company_report": {
    "id": 1,
    "company_id": 1,
    "user_id": 1,
    "reason": "Thiếu mức lương"
    }
    },
    "status_code": 200
    }
     *          )
     *      ),
     *      @OA\Response(
     *          response=404,
     *          description="Company report not found",
     *          ref="#/components/responses/NotFound"
     *      )
     *  )
     */
    public function getCompanyReportById(string $id): JsonResponse
    {
        try {
            $company_report = CompanyReport::where('id', $id)
                ->with('company_profile', 'user_profile', 'user')
                ->first();

            if (!$company_report) {
                return $this->respondNotFound();
            }

            return $this->respondWithData(
                [
                    'company_report' => $company_report,
                ]);
        }
        catch (Exception $exception) {
            return $this->respondInternalServerError($exception->getMessage());
        }
    }

    /**
     *  @OA\Post(
     *      path="/company-reports",
     *      summary="Create company report",
     *      tags={"Company Report"},
     *      security={{"sanctum":{}}},
     *      @OA\RequestBody(
     *          required=true,
     *          description="Create company report",
     *          @OA\JsonContent(
     *              required={"company_id", "reason"},
     *              example=
    {
    "company_id": 1,
    "reason": "reason"
    }
     *          )
     *      ),
     *      @OA\Response(
     *          response=201,
     *          description="Company report created",
     *          @OA\JsonContent(
     *              example=
    {
    "error": false,
    "message": "Tạo thành công",
    "data": {
    "company_report": {
    "company_id": "1",
    "user_id": 2,
    "reason": "reason",
    "id": 4
    }
    },
    "status_code": 201
    }
     *          )
     *      ),
     *      @OA\Response(
     *          response=400,
     *          description="Company report not created",
     *          ref="#/components/responses/BadRequest"
     *      )
     *  )
     */
    public function createCompanyReport(Request $request): JsonResponse
    {
        try {
            $company_report = new CompanyReport();
            $company_report->company_id = $request->company_id;
            $company_report->user_id = $request->user()->id;
            $company_report->reason = $request->reason;
            $company_report->save();

            return $this->respondCreated(
                [
                    'company_report' => $company_report,
                ]);
        }
        catch (Exception $exception) {
            return $this->respondInternalServerError($exception->getMessage());
        }
    }

    /**
     *  @OA\Delete(
     *      path="/company-reports/{id}",
     *      summary="Delete company report by id",
     *      tags={"Company Report"},
     *      security={{"sanctum":{}}},
     *      @OA\Parameter(
     *          name="id",
     *          in="path",
     *          description="Company report id",
     *          required=true,
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Company report deleted",
     *          @OA\JsonContent(
     *              example=
    {
    "error": false,
    "message": "Xoá thành công",
    "data": {
    "company_report": {
    "id": 4,
    "company_id": 1,
    "user_id": 2,
    "reason": "reason"
    }
    },
    "status_code": 200
    }
     *          )
     *      ),
     *      @OA\Response(
     *          response=404,
     *          description="Company report not found",
     *          ref="#/components/responses/NotFound"
     *      )
     *  )
     */
    public function deleteCompanyReport(string $id): JsonResponse
    {
        try {
            $company_report = CompanyReport::where('id', $id)->first();

            if (!$company_report) {
                return $this->respondNotFound();
            }

            $company_report->delete();

            return $this->respondWithData(
                [
                    'company_report' => $company_report,
                ], 'Xoá thành công');
        }
        catch (Exception $exception) {
            return $this->respondInternalServerError($exception->getMessage());
        }
    }
}
