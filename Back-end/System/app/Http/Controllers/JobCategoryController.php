<?php

namespace App\Http\Controllers;

use App\Models\JobCategory;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class JobCategoryController extends ApiController
{
    public function getAllJobCategory(): JsonResponse
    {
        try {
            $count_per_page = $request->count_per_page ?? 10;
            $order_by = $request->order_by ?? 'id';
            $order_type = $request->order_type ?? 'asc';

            $job_categories = JobCategory::orderBy($order_by, $order_type)
                ->paginate($count_per_page);

            if (count($job_categories) === 0) {
                return $this->respondNotFound();
            }

            return $this->respondWithData(
                [
                    'job_categories' => $job_categories,
                ]
            );
        }
        catch (Exception $exception) {
            return $this->respondInternalServerError($exception->getMessage());
        }
    }

    public function getJobCategoryByJobId(string $job_id): JsonResponse
    {
        try {
            $job_category = JobCategory::where('job_id', $job_id)->first();

            if (!$job_category) {
                return $this->respondNotFound();
            }

            return $this->respondWithData(
                [
                    'job_category' => $job_category,
                ]
            );
        }
        catch (Exception $exception) {
            return $this->respondInternalServerError($exception->getMessage());
        }
    }

    public function getJobCategoryByCategoryId(string $category_id): JsonResponse
    {
        try {
            $job_category = JobCategory::where('category_id', $category_id)->first();

            if (!$job_category) {
                return $this->respondNotFound();
            }

            return $this->respondWithData(
                [
                    'job_category' => $job_category,
                ]
            );
        }
        catch (Exception $exception) {
            return $this->respondInternalServerError($exception->getMessage());
        }
    }

    public function getJobCategoryById(string $id): JsonResponse
    {
        try {
            $job_category = JobCategory::where('id', $id)->first();

            if (!$job_category) {
                return $this->respondNotFound();
            }

            return $this->respondWithData(
                [
                    'job_category' => $job_category,
                ]
            );
        }
        catch (Exception $exception) {
            return $this->respondInternalServerError($exception->getMessage());
        }
    }

    public function createJobCategory(Request $request): JsonResponse
    {
        try {
            $job_id = $request->job_id;
            $category_ids = $request->category_id;

            $delete_job_category = JobCategory::where('job_id', $job_id)->delete();

            if (str_contains($category_ids, ';')) {
                while (str_contains($category_ids, ';;')) {
                    $category_ids = str_replace(';;', ';', $category_ids);
                }
                while (str_contains($category_ids, '; ')) {
                    $category_ids = str_replace('; ', ';', $category_ids);
                }
                $category_ids = explode(';', $category_ids);
            } else {
                $category_ids = [$category_ids];
            }

            foreach ($category_ids as $category_id) {
                $job_category = JobCategory::create([
                    'job_id' => $job_id,
                    'category_id' => $category_id,
                ]);
            }

            $job_category = JobCategory::where('job_id', $job_id)->get();

            return $this->respondWithData(
                [
                    'job_category' => $job_category,
                ]
            );
        }
        catch (Exception $exception) {
            return $this->respondInternalServerError($exception->getMessage());
        }
    }

    public function updateJobCategory(Request $request, string $id): JsonResponse
    {
        try {
            $job_category = JobCategory::where('id', $id)->first();

            if (!$job_category) {
                return $this->respondNotFound();
            }

            $job_category->update($request->all());

            return $this->respondWithData(
                [
                    'job_category' => $job_category,
                ]
            );
        }
        catch (Exception $exception) {
            return $this->respondInternalServerError($exception->getMessage());
        }
    }

    public function deleteJobCategory(string $id): JsonResponse
    {
        try {
            $job_category = JobCategory::where('id', $id)->first();

            if (!$job_category) {
                return $this->respondNotFound();
            }

            $job_category->delete();

            return $this->respondWithData(
                [
                    'job_category' => $job_category,
                ],
                'Xoá thành công'
            );
        }
        catch (Exception $exception) {
            return $this->respondInternalServerError($exception->getMessage());
        }
    }
}
