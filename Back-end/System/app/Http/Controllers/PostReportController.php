<?php

namespace App\Http\Controllers;

use App\Models\PostReport;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PostReportController extends ApiController
{
    public function createPostReport(Request $request): JsonResponse
    {
        try {
            $post_id = $request->post_id;
            $user_id = $request->user()->id;
            $reason = $request->reason;

            $post_report = new PostReport();
            $post_report->post_id = $post_id;
            $post_report->user_id = $user_id;
            $post_report->reason = $reason;

            $post_report->save();

            return $this->respondCreated(
                [
                    'post_report' => $post_report,
                ]);
        }
        catch (Exception $exception) {
            return $this->respondInternalServerError($exception->getMessage());
        }
    }
    public function getPostReports(Request $request): JsonResponse
    {
        try {
            $count_per_page = $request->count_per_page ?? 10;
            $order_by = $request->order_by ?? 'created_at';
            $order_type = $request->order_type ?? 'desc';

            $post_reports = PostReport::filter($request, PostReport::query())
                ->orderBy($order_by, $order_type)
                ->paginate($count_per_page);

            if (count($post_reports) === 0) {
                return $this->respondNotFound();
            }

            return $this->respondWithData(
                [
                    'post_reports' => $post_reports,
                ]);
        }
        catch (Exception $exception) {
            return $this->respondInternalServerError($exception->getMessage());
        }
    }

    public function getPostReportById(string $id): JsonResponse
    {
        try {
            $post_report = PostReport::where('id', $id)->first();

            if (!$post_report) {
                return $this->respondNotFound();
            }

            return $this->respondWithData(
                [
                    'post_report' => $post_report,
                ]);
        }
        catch (Exception $exception) {
            return $this->respondInternalServerError($exception->getMessage());
        }
    }

    public function deletePostReport(Request $request, string $id): JsonResponse
    {
        try {
            $post_report = PostReport::where('id', $id)->first();

            if (!$post_report) {
                return $this->respondNotFound();
            }

            if (!$request->user()->tokenCan('mod') && $post_report->user_id !== $request->user()->id) {
                return $this->respondForbidden('Bạn không có quyền xóa báo cáo này');
            }

            $post_report->delete();

            return $this->respondWithData(
                [
                    'post_report' => $post_report,
                ], 'Xoá thành công');
        }
        catch (Exception $exception) {
            return $this->respondInternalServerError($exception->getMessage());
        }
    }
}
