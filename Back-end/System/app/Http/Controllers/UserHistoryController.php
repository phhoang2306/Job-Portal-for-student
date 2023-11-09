<?php

namespace App\Http\Controllers;

use App\Models\UserHistory;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class UserHistoryController extends ApiController
{
    public function getUserHistories(Request $request): JsonResponse
    {
        try {
            $count_per_page = $request->count_per_page ?? 10;
            $order_by = $request->order_by ?? 'id';
            $order_type = $request->order_type ?? 'asc';

            $user_histories = UserHistory::filter($request, UserHistory::query())
                ->with(['user_profile', 'job'])
                ->orderBy($order_by, $order_type)
                ->paginate($count_per_page);

            if (count($user_histories) === 0) {
                return $this->respondNotFound();
            }

            return $this->respondWithData(
                [
                    'user_histories' => $user_histories,
                ]);
        }
        catch (Exception $exception) {
            return $this->respondInternalServerError($exception->getMessage());
        }
    }

    public function getUserHistoryById(Request $request, string $id): JsonResponse
    {
        try {
            $user_history = UserHistory::where('id', $id)->first();

            if (!$user_history) {
                return $this->respondNotFound();
            }

            return $this->respondWithData(
                [
                    'user_history' => $user_history,
                ]);
        }
        catch (Exception $exception) {
            return $this->respondInternalServerError($exception->getMessage());
        }
    }

    public function createUserHistory(Request $request): JsonResponse
    {
        try {
            $user_history = new UserHistory();
            $user_history->user_id = $request->user()->id;
            $user_history->job_id = $request->job_id;
            $user_history->times = $request->times;
            $user_history->save();

            return $this->respondCreated(
                [
                    'user_history' => $user_history,
                ]);
        }
        catch (Exception $exception) {
            return $this->respondInternalServerError($exception->getMessage());
        }
    }

    public function updateUserHistory(Request $request, string $id): JsonResponse
    {
        try {
            $user_history = UserHistory::where('id', $id)->first();

            if (!$user_history) {
                return $this->respondNotFound();
            }

            $user_history->times = $request->times ?? $user_history->times;
            $user_history->save();

            return $this->respondWithData(
                [
                    'user_history' => $user_history,
                ]);
        }
        catch (Exception $exception) {
            return $this->respondInternalServerError($exception->getMessage());
        }
    }

    public function deleteUserHistory(string $id): JsonResponse
    {
        try {
            $user_history = UserHistory::where('id', $id)->first();

            if (!$user_history) {
                return $this->respondNotFound();
            }

            $user_history->delete();

            return $this->respondWithData(
                [
                    'user_history' => $user_history,
                ], 'Xoá thành công');
        }
        catch (Exception $exception) {
            return $this->respondInternalServerError($exception->getMessage());
        }
    }
}
