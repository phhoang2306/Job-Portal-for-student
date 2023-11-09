<?php

namespace App\Http\Controllers;

use App\Models\CV;
use App\Models\Post;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PostController extends ApiController
{
    public function createPost(Request $request): JsonResponse
    {
        try {
            $content = $request->post_content;

            $post = new Post();
            $post->cv_id = $request->cv_id;
            $post->title = $request->title;
            $post->content = $content;
            $post->user_id = $request->user()->id;

            $post->save();

            return $this->respondCreated(
                [
                    'post' => $post,
                ]);
        }
        catch (Exception $exception) {
            return $this->respondInternalServerError($exception->getMessage());
        }
    }

    public function getPostById(Request $request, string $id): JsonResponse
    {
        try {
            $post = Post::where('id', $id)->first();

            if (!$post) {
                return $this->respondNotFound();
            }

            return $this->respondWithData(
                [
                    'post' => $post,
                ]);
        }
        catch (Exception $exception) {
            return $this->respondInternalServerError($exception->getMessage());
        }
    }

    public function getAllPosts(Request $request): JsonResponse
    {
        try {
            $count_per_page = $request->count_per_page ?? 10;
            $order_by = $request->order_by ?? 'id';
            $order_type = $request->order_type ?? 'asc';

            $posts = Post::orderBy($order_by, $order_type)->paginate($count_per_page);

            if (count($posts) === 0) {
                return $this->respondNotFound();
            }

            return $this->respondWithData(
                [
                    'posts' => $posts,
                ]);
        }
        catch (Exception $exception) {
            return $this->respondInternalServerError($exception->getMessage());
        }
    }

    public function getPostsByUserId(Request $request, string $user_id): JsonResponse
    {
        try {
            $count_per_page = $request->count_per_page ?? 10;
            $order_by = $request->order_by ?? 'id';
            $order_type = $request->order_type ?? 'asc';

            $posts = Post::where('user_id', $user_id)
                ->orderBy($order_by, $order_type)
                ->paginate($count_per_page);

            if (count($posts) === 0) {
                return $this->respondNotFound();
            }

            return $this->respondWithData(
                [
                    'posts' => $posts,
                ]);
        }
        catch (Exception $exception) {
            return $this->respondInternalServerError($exception->getMessage());
        }
    }

    public function updatePost(Request $request, string $id): JsonResponse
    {
        try {
            $post = Post::where('id', $id)->first();

            if (!$post) {
                return $this->respondNotFound();
            }

            $post->title = $request->title ?? $post->title;
            $post->content = $request->post_content ?? $post->content;
            $post->save();

            return $this->respondWithData(
                [
                    'post' => $post,
                ]);
        }
        catch (Exception $exception) {
            return $this->respondInternalServerError($exception->getMessage());
        }
    }

    public function updatePostVotes(Request $request, string $id): JsonResponse
    {
        try {
            $post = Post::where('id', $id)->first();

            if (!$post) {
                return $this->respondNotFound();
            }

            $post->upvote = $request->upvote;
            $post->downvote = $request->downvote;
            $post->save();

            return $this->respondWithData(
                [
                    'post' => $post,
                ]);
        }
        catch (Exception $exception) {
            return $this->respondInternalServerError($exception->getMessage());
        }
    }

    public function deletePost(Request $request, string $id): JsonResponse
    {
        try {
            $post = Post::where('id', $id)->first();

            if (!$post) {
                return $this->respondNotFound();
            }

            $post->delete();

            return $this->respondWithData(
                [
                    'post' => $post,
                ], 'Xoá thành công');
        }
        catch (Exception $exception) {
            return $this->respondInternalServerError($exception->getMessage());
        }
    }
}
