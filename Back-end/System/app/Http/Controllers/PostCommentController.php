<?php

namespace App\Http\Controllers;

use App\Models\PostComment;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PostCommentController extends ApiController
{
    public function createPostComment(Request $request): JsonResponse
    {
        try {
            $comment = new PostComment();
            $comment->post_id = $request->post_id;
            $comment->user_id = $request->user()->id;
            $comment->content = $request->comment_content;

            $comment->save();

            return $this->respondCreated(
                [
                    'comment' => $comment,
                ]);
        }
        catch (Exception $exception) {
            return $this->respondInternalServerError($exception->getMessage());
        }
    }

    public function getPostComments(Request $request): JsonResponse
    {
        try {
            $count_per_page = $request->count_per_page ?? 10;
            $order_by = $request->order_by ?? 'created_at';
            $order_type = $request->order_type ?? 'desc';

            $comments = PostComment::filter($request, PostComment::query())
                ->orderBy($order_by, $order_type)
                ->paginate($count_per_page);

            return $this->respondWithData(
                [
                    'comments' => $comments,
                ]);
        }
        catch (Exception $exception) {
            return $this->respondInternalServerError($exception->getMessage());
        }
    }

    public function getPostCommentById(string $id): JsonResponse
    {
        try {
            $comment = PostComment::where('id', $id)->first();

            if (!$comment) {
                return $this->respondNotFound();
            }

            return $this->respondWithData(
                [
                    'comment' => $comment,
                ]);
        }
        catch (Exception $exception) {
            return $this->respondInternalServerError($exception->getMessage());
        }
    }

    public function deletePostComment(Request $request, string $id): JsonResponse
    {
        try {
            $comment = PostComment::where('id', $id)->first();

            if (!$comment) {
                return $this->respondNotFound();
            }

            if (!$request->user()->tokenCan('mod') && $comment->user_id !== $request->user()->id) {
                return $this->respondForbidden('Bạn không có quyền xóa comment này');
            }

            $comment->delete();

            return $this->respondWithData(
                [
                    'comment' => $comment,
                ], 'Xoá thành công');
        }
        catch (Exception $exception) {
            return $this->respondInternalServerError($exception->getMessage());
        }
    }
}
