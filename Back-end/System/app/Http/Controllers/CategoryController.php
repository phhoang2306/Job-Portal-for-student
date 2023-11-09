<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CategoryController extends ApiController
{
    public function getCategories(Request $request): JsonResponse
    {
        try {
            $count_per_page = $request->count_per_page ?? 10;
            $order_by = $request->order_by ?? 'id';
            $order_type = $request->order_type ?? 'asc';

            $categories = Category::orderBy($order_by, $order_type)
                ->paginate($count_per_page);

            if (count($categories) === 0) {
                return $this->respondNotFound();
            }

            return $this->respondWithData(
                [
                    'categories' => $categories,
                ]
            );
        }
        catch (Exception $exception) {
            return $this->respondInternalServerError($exception->getMessage());
        }
    }

    public function getCategoryById(Request $request, string $id): JsonResponse
    {
        try {
            $category = Category::where('id', $id)->first();

            if (!$category) {
                return $this->respondNotFound();
            }

            return $this->respondWithData(
                [
                    'category' => $category,
                ]
            );
        }
        catch (Exception $exception) {
            return $this->respondInternalServerError($exception->getMessage());
        }
    }

    public function createCategory(Request $request): JsonResponse
    {
        try {
            $category = Category::create($request->all());

            return $this->respondWithData(
                [
                    'category' => $category,
                ]
            );
        }
        catch (Exception $exception) {
            return $this->respondInternalServerError($exception->getMessage());
        }
    }

    public function updateCategory(Request $request, string $id): JsonResponse
    {
        try {
            $category = Category::where('id', $id)->first();

            if (!$category) {
                return $this->respondNotFound();
            }

            $category->update($request->all());

            return $this->respondWithData(
                [
                    'category' => $category,
                ]
            );
        }
        catch (Exception $exception) {
            return $this->respondInternalServerError($exception->getMessage());
        }
    }

    public function deleteCategory(Request $request, string $id): JsonResponse
    {
        try {
            $category = Category::where('id', $id)->first();

            if (!$category) {
                return $this->respondNotFound();
            }

            $category->delete();

            return $this->respondWithData(
                [
                    'category' => $category,
                ],
                'Xoá thành công'
            );
        }
        catch (Exception $exception) {
            return $this->respondInternalServerError($exception->getMessage());
        }
    }
}
