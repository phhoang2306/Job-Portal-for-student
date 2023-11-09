<?php

namespace App\Http\Controllers;

use App\Models\JobReport;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class JobReportController extends ApiController
{
    /**
     *  @OA\Get(
     *      path="/api/job-reports",
     *      tags={"Job Reports"},
     *      summary="Get all job reports",
     *      @OA\Parameter(
     *          name="count_per_page",
     *          in="query",
     *          description="Number of job reports per page",
     *          required=false,
     *      ),
     *      @OA\Parameter(
     *          name="Accept",
     *          in="header",
     *          description="application/json",
     *          required=false
     *      ),
     *      @OA\Parameter(
     *          name="Authorization",
     *          in="header",
     *          description="Bearer {token}",
     *          required=true
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Successfully retrieved job reports",
     *          @OA\JsonContent(
     *              example=
    {
    "error": false,
    "message": "Xử lí thành công",
    "data": {
    "job_reports": {
    "current_page": 1,
    "data": {
    {
    "id": 1,
    "job_id": 1,
    "user_id": 1,
    "reason": "Fake Job"
    }
    },
    "first_page_url": "http://localhost:8000/api/job-reports?page=1",
    "from": 1,
    "last_page": 22,
    "last_page_url": "http://localhost:8000/api/job-reports?page=22",
    "links": {
    {
    "url": null,
    "label": "&laquo; Previous",
    "active": false
    },
    {
    "url": "http://localhost:8000/api/job-reports?page=1",
    "label": "1",
    "active": true
    },
    {
    "url": "http://localhost:8000/api/job-reports?page=2",
    "label": "2",
    "active": false
    },
    {
    "url": "http://localhost:8000/api/job-reports?page=3",
    "label": "3",
    "active": false
    },
    {
    "url": "http://localhost:8000/api/job-reports?page=4",
    "label": "4",
    "active": false
    },
    {
    "url": "http://localhost:8000/api/job-reports?page=5",
    "label": "5",
    "active": false
    },
    {
    "url": "http://localhost:8000/api/job-reports?page=6",
    "label": "6",
    "active": false
    },
    {
    "url": "http://localhost:8000/api/job-reports?page=7",
    "label": "7",
    "active": false
    },
    {
    "url": "http://localhost:8000/api/job-reports?page=8",
    "label": "8",
    "active": false
    },
    {
    "url": "http://localhost:8000/api/job-reports?page=9",
    "label": "9",
    "active": false
    },
    {
    "url": "http://localhost:8000/api/job-reports?page=10",
    "label": "10",
    "active": false
    },
    {
    "url": null,
    "label": "...",
    "active": false
    },
    {
    "url": "http://localhost:8000/api/job-reports?page=21",
    "label": "21",
    "active": false
    },
    {
    "url": "http://localhost:8000/api/job-reports?page=22",
    "label": "22",
    "active": false
    },
    {
    "url": "http://localhost:8000/api/job-reports?page=2",
    "label": "Next &raquo;",
    "active": false
    }
    },
    "next_page_url": "http://localhost:8000/api/job-reports?page=2",
    "path": "http://localhost:8000/api/job-reports",
    "per_page": 1,
    "prev_page_url": null,
    "to": 1,
    "total": 22
    }
    },
    "status_code": 200
    }
     *          ),
     *      ),
     *      @OA\Response(
     *          response=404,
     *          description="No job reports found",
     *          ref="#/components/responses/NotFound",
     *      ),
     *  )
     */
    public function getJobReports(Request $request): JsonResponse
    {
        try {
            $count_per_page = $request->count_per_page ?? 10;
            $order_by = $request->order_by ?? 'id';
            $order_type = $request->order_type ?? 'asc';

            $job_reports = JobReport::filter($request, JobReport::query())
                ->with('job', 'user_profile', 'user')
                ->orderBy($order_by, $order_type)
                ->paginate($count_per_page);

            if (count($job_reports) === 0) {
                return $this->respondNotFound();
            }

            return $this->respondWithData(
                [
                    'job_reports' => $job_reports,
                ]);
        }
        catch (Exception $exception) {
            return $this->respondInternalServerError($exception->getMessage());
        }
    }

    /**
     *  @OA\Get(
     *      path="/api/job-reports/{id}",
     *      tags={"Job Reports"},
     *      summary="Get job report by id",
     *      @OA\Parameter(
     *          name="id",
     *          in="path",
     *          description="Job report id",
     *          required=true,
     *      ),
     *      @OA\Parameter(
     *          name="count_per_page",
     *          in="query",
     *          description="Number of job reports per page",
     *          required=false,
     *      ),
     *      @OA\Parameter(
     *          name="Accept",
     *          in="header",
     *          description="application/json",
     *          required=false
     *      ),
     *      @OA\Parameter(
     *          name="Authorization",
     *          in="header",
     *          description="Bearer {token}",
     *          required=true
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Successfully retrieved job report",
     *          @OA\JsonContent(
     *              example=
    {
    "error": false,
    "message": "Xử lí thành công",
    "data": {
    "job_report": {
    "current_page": 1,
    "data": {
    {
    "id": 1,
    "job_id": 1,
    "user_id": 1,
    "reason": "Fake Job"
    }
    },
    "first_page_url": "http://localhost:8000/api/job-reports/1?page=1",
    "from": 1,
    "last_page": 1,
    "last_page_url": "http://localhost:8000/api/job-reports/1?page=1",
    "links": {
    {
    "url": null,
    "label": "&laquo; Previous",
    "active": false
    },
    {
    "url": "http://localhost:8000/api/job-reports/1?page=1",
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
    "path": "http://localhost:8000/api/job-reports/1",
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
     *          description="Job report not found",
     *          ref="#/components/responses/NotFound",
     *      ),
     *  )
     */
    public function getJobReportById(string $id): JsonResponse
    {
        try {
            $job_report = JobReport::where('id', $id)
                ->with('job', 'user_profile', 'user')
                ->first();

            if (!$job_report) {
                return $this->respondNotFound();
            }

            return $this->respondWithData(
                [
                    'job_report' => $job_report,
                ]);
        }
        catch (Exception $exception) {
            return $this->respondInternalServerError($exception->getMessage());
        }
    }

    /**
     *  @OA\Post(
     *      path="/api/job-reports",
     *      tags={"Job Reports"},
     *      summary="Create job report",
     *      @OA\Parameter(
     *          name="Accept",
     *          in="header",
     *          description="application/json",
     *          required=false
     *      ),
     *      @OA\Parameter(
     *          name="Authorization",
     *          in="header",
     *          description="Bearer {token}",
     *          required=true
     *      ),
     *      @OA\RequestBody(
     *          required=true,
     *          @OA\JsonContent(
     *              example=
    {
    "job_id": 1,
    "reason": "abcdef"
    }
     *      ),
     *      @OA\Response(
     *          response=201,
     *          description="Successfully created job report",
     *          @OA\JsonContent(
     *              example=
    {
    "error": false,
    "message": "Tạo thành công",
    "data": {
    "job_report": {
    "job_id": "1",
    "user_id": 1,
    "reason": "abcdef",
    "id": 23
    }
    },
    "status_code": 201
    }
     *              ),
     *          ),
     *      ),
     *      @OA\Response(
     *          response=400,
     *          description="Invalid job report data",
     *          ref="#/components/responses/BadRequest",
     *      ),
     *  )
     */
    public function createJobReport(Request $request): JsonResponse
    {
        try {
            $job_report = new JobReport();
            $job_report->job_id = $request->job_id;
            $job_report->user_id = $request->user()->id;
            $job_report->reason = $request->reason;

            $job_report->save();

            return $this->respondCreated(
                [
                    'job_report' => $job_report,
                ]);
        }
        catch (Exception $exception) {
            return $this->respondInternalServerError($exception->getMessage());
        }
    }

    /**
     *  @OA\Delete(
     *      path="/api/job-reports/{id}",
     *      tags={"Job Reports"},
     *      summary="Delete job report",
     *      @OA\Parameter(
     *          name="id",
     *          in="path",
     *          description="Job report id",
     *          required=true,
     *      ),
     *      @OA\Parameter(
     *          name="Accept",
     *          in="header",
     *          description="application/json",
     *          required=false
     *      ),
     *      @OA\Parameter(
     *          name="Authorization",
     *          in="header",
     *          description="Bearer {token}",
     *          required=true
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Successfully deleted job report",
     *          @OA\JsonContent(
     *              example=
    {
    "error": false,
    "message": "Xoá thành công",
    "data": {
    "job_report": {
    "id": 23,
    "job_id": 1,
    "user_id": 1,
    "reason": "abcdef"
    }
    },
    "status_code": 200
    }
     *          ),
     *      ),
     *      @OA\Response(
     *          response=404,
     *          description="Job report not found",
     *          ref="#/components/responses/NotFound",
     *      ),
     *  )
     */
    public function deleteJobReport(Request $request, string $id): JsonResponse
    {
        try {
            $job_report = JobReport::where('id', $id)->first();

            if (!$job_report) {
                return $this->respondNotFound();
            }

            $job_report->delete();

            return $this->respondWithData(
                [
                    'job_report' => $job_report,
                ]
                , 'Xoá thành công');
        }
        catch (Exception $exception) {
            return $this->respondInternalServerError($exception->getMessage());
        }
    }
}
