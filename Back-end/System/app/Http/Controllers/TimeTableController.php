<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateTimeTableRequest;
use App\Models\TimeTable;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class TimeTableController extends ApiController
{
    public function getAllTimeTables(Request $request): JsonResponse
    {
        try {
            $count_per_page = $request->count_per_page ?? 10;

            $time_tables = TimeTable::paginate($count_per_page);

            if ($time_tables->count() === 0) {
                return $this->respondNotFound();
            }

            return $this->respondWithData([
                'time_tables' => $time_tables,
            ]);
        }
        catch (Exception $exception) {
            return $this->respondInternalServerError($exception->getMessage());
        }
    }

    public function getTimeTablesByUserId(Request $request, string $user_id): JsonResponse
    {
        try {
            $time_tables = TimeTable::where('user_id', $user_id)
                ->get();

            if ($time_tables->count() === 0) {
                return $this->respondNotFound();
            }

            return $this->respondWithData([
                'time_tables' => $time_tables,
            ]);
        }
        catch (Exception $exception) {
            return $this->respondInternalServerError($exception->getMessage());
        }
    }

    public function getTimeTableById(Request $request, string $id): JsonResponse
    {
        try {
            $time_table = TimeTable::where('id', $id)->first();

            if (!$time_table) {
                return $this->respondNotFound();
            }

            return $this->respondWithData([
                'time_table' => $time_table,
            ]);
        }
        catch (Exception $exception) {
            return $this->respondInternalServerError($exception->getMessage());
        }
    }

    public function createTimeTable(CreateTimeTableRequest $request): JsonResponse
    {
        try {
            $time_table = new TimeTable();
            $time_table->user_id = $request->user()->id;
            $time_table->coordinate = $request->coordinate;
            $time_table->save();

            return $this->respondCreated([
                'time_table' => $time_table ?? null,
            ]);
        }
        catch (Exception $exception) {
            return $this->respondInternalServerError($exception->getMessage());
        }
    }

    public function updateTimeTable(Request $request, string $id): JsonResponse
    {
        try {
            $time_table = TimeTable::where('id', $id)->first();

            if (!$time_table) {
                return $this->respondNotFound();
            }

            $time_table->coordinate = $request->coordinate ?? '';
            $time_table->save();

            return $this->respondWithData([
                'time_table' => $time_table,
            ]);
        }
        catch (Exception $exception) {
            return $this->respondInternalServerError($exception->getMessage());
        }
    }

    public function deleteTimeTable(Request $request, string $id): JsonResponse
    {
        try {
            $time_table = TimeTable::where('id', $id)->first();

            if (!$time_table) {
                return $this->respondNotFound();
            }

            $time_table->delete();

            return $this->respondWithData([
                'time_table' => $time_table,
            ], 'Xoá thành công');
        }
        catch (Exception $exception) {
            return $this->respondInternalServerError($exception->getMessage());
        }
    }
}
