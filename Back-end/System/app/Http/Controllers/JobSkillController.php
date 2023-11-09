<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateJobSkillRequest;
use App\Models\JobSkill;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class JobSkillController extends ApiController
{
    /**
     *  @OA\Get(
     *      path="/api/job-skills",
     *      tags={"Job Skills"},
     *      summary="Get all job skills",
     *      @OA\Parameter(
     *          name="count_per_page",
     *          in="query",
     *          description="Number of job skills per page",
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
     *          description="Successfully retrieved job skills",
     *          @OA\JsonContent(
     *              example=
    {
    "error": false,
    "message": "Xử lí thành công",
    "data": {
    "job_skills": {
    "current_page": 1,
    "data": {
    {
    "id": 1,
    "job_id": 1,
    "skill": "PowerPoint"
    }
    },
    "first_page_url": "http://localhost:8000/api/job-skills?page=1",
    "from": 1,
    "last_page": 136,
    "last_page_url": "http://localhost:8000/api/job-skills?page=136",
    "links": {
    {
    "url": null,
    "label": "&laquo; Previous",
    "active": false
    },
    {
    "url": "http://localhost:8000/api/job-skills?page=1",
    "label": "1",
    "active": true
    },
    {
    "url": "http://localhost:8000/api/job-skills?page=2",
    "label": "2",
    "active": false
    },
    {
    "url": "http://localhost:8000/api/job-skills?page=3",
    "label": "3",
    "active": false
    },
    {
    "url": "http://localhost:8000/api/job-skills?page=4",
    "label": "4",
    "active": false
    },
    {
    "url": "http://localhost:8000/api/job-skills?page=5",
    "label": "5",
    "active": false
    },
    {
    "url": "http://localhost:8000/api/job-skills?page=6",
    "label": "6",
    "active": false
    },
    {
    "url": "http://localhost:8000/api/job-skills?page=7",
    "label": "7",
    "active": false
    },
    {
    "url": "http://localhost:8000/api/job-skills?page=8",
    "label": "8",
    "active": false
    },
    {
    "url": "http://localhost:8000/api/job-skills?page=9",
    "label": "9",
    "active": false
    },
    {
    "url": "http://localhost:8000/api/job-skills?page=10",
    "label": "10",
    "active": false
    },
    {
    "url": null,
    "label": "...",
    "active": false
    },
    {
    "url": "http://localhost:8000/api/job-skills?page=135",
    "label": "135",
    "active": false
    },
    {
    "url": "http://localhost:8000/api/job-skills?page=136",
    "label": "136",
    "active": false
    },
    {
    "url": "http://localhost:8000/api/job-skills?page=2",
    "label": "Next &raquo;",
    "active": false
    }
    },
    "next_page_url": "http://localhost:8000/api/job-skills?page=2",
    "path": "http://localhost:8000/api/job-skills",
    "per_page": 1,
    "prev_page_url": null,
    "to": 1,
    "total": 136
    }
    },
    "status_code": 200
    }
     *          )
     *      ),
     *      @OA\Response(
     *          response=404,
     *          description="No job skills found",
     *          ref="#/components/responses/NotFound"
     *      ),
     *  )
     */
    public function getAllJobSkills(Request $request): JsonResponse
    {
        try {
            $count_per_page = $request->count_per_page ?? 10;
            $order_by = $request->order_by ?? 'id';
            $order_type = $request->order_type ?? 'asc';

            $job_skills = JobSkill::orderBy($order_by, $order_type)->paginate($count_per_page);

            if (count($job_skills) === 0) {
                return $this->respondNotFound();
            }

            return $this->respondWithData(
                [
                    'job_skills' => $job_skills,
                ]);
        }
        catch (Exception $exception) {
            return $this->respondInternalServerError($exception->getMessage());
        }
    }

    /**
     *  @OA\Get(
     *      path="/api/job-skills/job/{job_id}",
     *      tags={"Job Skills"},
     *      summary="Get job skills by job id",
     *      @OA\Parameter(
     *          name="job_id",
     *          in="path",
     *          description="Id of job",
     *          required=true,
     *      ),
     *      @OA\Parameter(
     *          name="count_per_page",
     *          in="query",
     *          description="Number of job skills per page",
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
     *          description="Successfully retrieved job skills",
     *          @OA\JsonContent(
     *              example=
    {
    "error": false,
    "message": "Xử lí thành công",
    "data": {
    "job_skills": {
    "current_page": 1,
    "data": {
    {
    "id": 1,
    "job_id": 1,
    "skill": "PowerPoint"
    }
    },
    "first_page_url": "http://localhost:8000/api/job-skills/job/1?page=1",
    "from": 1,
    "last_page": 3,
    "last_page_url": "http://localhost:8000/api/job-skills/job/1?page=3",
    "links": {
    {
    "url": null,
    "label": "&laquo; Previous",
    "active": false
    },
    {
    "url": "http://localhost:8000/api/job-skills/job/1?page=1",
    "label": "1",
    "active": true
    },
    {
    "url": "http://localhost:8000/api/job-skills/job/1?page=2",
    "label": "2",
    "active": false
    },
    {
    "url": "http://localhost:8000/api/job-skills/job/1?page=3",
    "label": "3",
    "active": false
    },
    {
    "url": "http://localhost:8000/api/job-skills/job/1?page=2",
    "label": "Next &raquo;",
    "active": false
    }
    },
    "next_page_url": "http://localhost:8000/api/job-skills/job/1?page=2",
    "path": "http://localhost:8000/api/job-skills/job/1",
    "per_page": 1,
    "prev_page_url": null,
    "to": 1,
    "total": 3
    }
    },
    "status_code": 200
    }
     *          ),
     *      ),
     *      @OA\Response(
     *          response=404,
     *          description="No job skills found",
     *          ref="#/components/responses/NotFound"
     *      ),
     *  )
     */
    public function getJobSkillsByJobId(Request $request, string $job_id): JsonResponse
    {
        try {
            $count_per_page = $request->count_per_page ?? 10;
            $order_by = $request->order_by ?? 'id';
            $order_type = $request->order_type ?? 'asc';

            $job_skills = JobSkill::where('job_id', $job_id)
                ->orderBy($order_by, $order_type)
                ->paginate($count_per_page);

            if (count($job_skills) === 0) {
                return $this->respondNotFound();
            }

            return $this->respondWithData(
                [
                    'job_skills' => $job_skills,
                ]);
        }
        catch (Exception $exception) {
            return $this->respondInternalServerError($exception->getMessage());
        }
    }

    /**
     *  @OA\Get(
     *      path="/api/job-skills/{id}",
     *      tags={"Job Skills"},
     *      summary="Get job skill by id",
     *      @OA\Parameter(
     *          name="id",
     *          in="path",
     *          description="Id of job skill",
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
     *          description="Successfully retrieved job skill",
     *          @OA\JsonContent(
     *              example=
    {
    "error": false,
    "message": "Xử lí thành công",
    "data": {
    "job_skill": {
    "id": 1,
    "job_id": 1,
    "skill": "PowerPoint"
    }
    },
    "status_code": 200
    }
     *          ),
     *      ),
     *      @OA\Response(
     *          response=404,
     *          description="Job skill not found",
     *          ref="#/components/responses/NotFound"
     *      ),
     *  )
     */
    public function getJobSkillById(Request $request, string $id): JsonResponse
    {
        try {
            $job_skill = JobSkill::where('id', $id)->first();

            if (!$job_skill) {
                return $this->respondNotFound();
            }

            return $this->respondWithData(
                [
                    'job_skill' => $job_skill,
                ]);
        }
        catch (Exception $exception) {
            return $this->respondInternalServerError($exception->getMessage());
        }
    }

    /**
     *  @OA\Post(
     *      path="/api/job-skills",
     *      tags={"Job Skills"},
     *      summary="Create job skill",
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
    "job_id": 1,
    "skill": "abcdef"
    }
     *          ),
     *      ),
     *      @OA\Response(
     *          response=201,
     *          description="Successfully created job skill",
     *          @OA\JsonContent(
     *              example=
    {
    "error": false,
    "message": "Tạo thành công",
    "data": {
    "job_skill": {
    "job_id": "1",
    "skill": "abcdef",
    "id": 137
    }
    },
    "status_code": 201
    }
     *          ),
     *      ),
     *      @OA\Response(
     *          response=400,
     *          description="Invalid job skill data",
     *          ref="#/components/responses/BadRequest"
     *      ),
     *  )
     */
    public function createJobSkill(CreateJobSkillRequest $request): JsonResponse
    {
        try {
            $job_id = $request->job_id;
            $skills = $request->skill;

            $delete_skills = JobSkill::where('job_id', $job_id)->delete();

            if (str_contains($skills, ';')) {
                while (str_contains($skills, ';;')) {
                    $skills = str_replace(';;', ';', $skills);
                }
                while (str_contains($skills, '; ')) {
                    $skills = str_replace('; ', ';', $skills);
                }
                $skills = explode(';', $skills);
            }
            else {
                $skills = [$skills];
            }

            foreach ($skills as $skill) {
                JobSkill::create([
                    'job_id' => $job_id,
                    'skill' => $skill,
                ]);
            }

            $job_skill = JobSkill::where('job_id', $job_id)->get();

            return $this->respondCreated(
                [
                    'job_skill' => $job_skill,
                ]);
        }
        catch (Exception $exception) {
            return $this->respondInternalServerError($exception->getMessage());
        }
    }

    /**
     *  @OA\Put(
     *      path="/api/job-skills/{id}",
     *      tags={"Job Skills"},
     *      summary="Update job skill",
     *      @OA\Parameter(
     *          name="id",
     *          in="path",
     *          description="Id of job skill",
     *          required=true,
     *      ),
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
    "job_id": 1,
    "skill": "abc"
    }
     *          ),
     *      ),
     *      @OA\Response(
     *          response=201,
     *          description="Successfully created job skill",
     *          @OA\JsonContent(
     *              example=
    {
    "error": false,
    "message": "Xử lí thành công",
    "data": {
    "job_skill": {
    "id": 137,
    "job_id": "1",
    "skill": "abc"
    }
    },
    "status_code": 200
    }
     *          ),
     *      ),
     *      @OA\Response(
     *          response=400,
     *          description="Invalid job skill data",
     *          ref="#/components/responses/BadRequest"
     *      ),
     *  )
     */
    public function updateJobSkill(Request $request, string $id): JsonResponse
    {
        try {
            $job_skill = JobSkill::where('id', $id)->first();

            if (!$job_skill) {
                return $this->respondNotFound();
            }

            $job_skill->job_id = $request->job_id ?? $job_skill->job_id;
            $job_skill->skill = $request->skill ?? $job_skill->skill;
            $job_skill->save();

            return $this->respondWithData(
                [
                    'job_skill' => $job_skill,
                ]);
        }
        catch (Exception $exception) {
            return $this->respondInternalServerError($exception->getMessage());
        }
    }

    /**
     *  @OA\Delete(
     *      path="/api/job-skills/{id}",
     *      tags={"Job Skills"},
     *      summary="Delete a job skill",
     *      @OA\Parameter(
     *          name="id",
     *          in="path",
     *          description="Job skill id",
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
     *          description="Successfully deleted job skill",
     *          @OA\JsonContent(
     *              example=
    {
    "error": false,
    "message": "Xử lí thành công",
    "data": {
    "job_skill": {
    "id": 137,
    "job_id": 1,
    "skill": "abc"
    }
    },
    "status_code": 200
    }
     *          ),
     *      ),
     *      @OA\Response(
     *          response=404,
     *          description="Job skill not found",
     *          ref="#/components/responses/NotFound"
     *      ),
     *  )
     */
    public function deleteJobSkill(string $id): JsonResponse
    {
        try {
            $job_skill = JobSkill::where('id', $id)->first();

            if (!$job_skill) {
                return $this->respondNotFound();
            }

            $job_skill->delete();

            return $this->respondWithData(
                [
                    'job_skill' => $job_skill,
                ], 'Xóa thành công');
        }
        catch (Exception $exception) {
            return $this->respondInternalServerError($exception->getMessage());
        }
    }
}
