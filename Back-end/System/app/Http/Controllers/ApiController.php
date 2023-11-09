<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;

class ApiController extends Controller
{
    const STATUS_CODE_SUCCESS = 200;
    const STATUS_CODE_CREATED = 201;
    const STATUS_CODE_NO_CONTENT = 204;
    const STATUS_CODE_BAD_REQUEST = 400;
    const STATUS_CODE_UNAUTHORIZED = 401;
    const STATUS_CODE_FORBIDDEN = 403;
    const STATUS_CODE_NOT_FOUND = 404;
    const STATUS_CODE_METHOD_NOT_ALLOWED = 405;
    const STATUS_CODE_UNPROCESSABLE_ENTITY = 422;
    const STATUS_CODE_INTERNAL_SERVER_ERROR = 500;

    protected $statusCode = 200;

    public function __construct()
    {
        $this->statusCode = 200;
    }

    public function getStatusCode()
    {
        return $this->statusCode;
    }

    public function setStatusCode($statusCode): static
    {
        $this->statusCode = $statusCode;
        return $this;
    }

    public function respondWithData($data, $message = 'Xử lí thành công'): JsonResponse
    {
        $res = [
            'error'         => false,
            'message'       => $message,
            'data'          => $data,
            'status_code'   => $this->getStatusCode()
        ];
        return response()->json($res, $this->getStatusCode())
            ->withHeaders([
                'Content-Type' => 'application/json',
                'Charset' => 'utf-8',
                'Access-Control-Allow-Origin' => '*',
                'Access-Control-Allow-Headers' => '*',
                'Access-Control-Allow-Methods' => 'GET, POST, PUT, DELETE, OPTIONS, HEAD',
                'Allow' => 'GET, POST, PUT, DELETE, OPTIONS, HEAD'
            ]);
    }

    /**
     *  @OA\Response(
     *      response="Error",
     *      description="Xảy ra lỗi",
     *      @OA\JsonContent(
     *          example={"error": true, "message": "Xảy ra lỗi", "data": null, "status_code": 404}
     *      )
     *  )
     */
    public function respondWithError($message = 'Xảy ra lỗi'): JsonResponse
    {
        $res = [
            'error'         => true,
            'message'       => $message,
            'data'          => null,
            'status_code'   => $this->getStatusCode()
        ];

        return response()->json($res, $this->getStatusCode())
            ->withHeaders([
                'Content-Type' => 'application/json',
                'Charset' => 'utf-8',
                'Access-Control-Allow-Origin' => '*',
                'Access-Control-Allow-Headers' => '*',
                'Access-Control-Allow-Methods' => 'GET, POST, PUT, DELETE, OPTIONS, HEAD',
                'Allow' => 'GET, POST, PUT, DELETE, OPTIONS, HEAD'
            ]);
    }

    /**
     *  @OA\Response(
     *      response="NotFound",
     *      description="Không tìm thấy",
     *      @OA\JsonContent(
     *          example={"error": true, "message": "Không tìm thấy", "data": null, "status_code": 404}
     *      )
     *  )
     */
    public function respondNotFound($message = 'Không tìm thấy'): JsonResponse
    {
        return $this->setStatusCode(self::STATUS_CODE_NOT_FOUND)->respondWithError($message);
    }

    /**
     *  @OA\Response(
     *      response="InternalServerError",
     *      description="Lỗi server",
     *      @OA\JsonContent(
     *          example={"error": true, "message": "Lỗi server", "data": null, "status_code": 500}
     *      )
     *  )
     */
    public function respondInternalServerError($message = 'Lỗi server'): JsonResponse
    {
        return $this->setStatusCode(self::STATUS_CODE_INTERNAL_SERVER_ERROR)->respondWithError($message);
    }

    /**
     *  @OA\Response(
     *      response="Unauthorized",
     *      description="Không có quyền",
     *      @OA\JsonContent(
     *          example={"error": true, "message": "Không có quyền", "data": null, "status_code": 401}
     *      )
     *  )
     */
    public function respondUnauthorized($message = 'Không có quyền'): JsonResponse
    {
        return $this->setStatusCode(self::STATUS_CODE_UNAUTHORIZED)->respondWithError($message);
    }

    /**
     *  @OA\Response(
     *      response="Forbidden",
     *      description="Bị cấm",
     *      @OA\JsonContent(
     *          example={"error": true, "message": "Bị cấm", "data": null, "status_code": 403}
     *      )
     *  )
     */
    public function respondForbidden($message = 'Bị cấm'): JsonResponse
    {
        return $this->setStatusCode(self::STATUS_CODE_FORBIDDEN)->respondWithError($message);
    }

    /**
     *  @OA\Response(
     *      response="BadRequest",
     *      description="Yêu cầu không hợp lệ",
     *      @OA\JsonContent(
     *          example={"error": true, "message": "Yêu cầu không hợp lệ", "data": null, "status_code": 400}
     *      )
     *  )
     */
    public function respondBadRequest($message = 'Yêu cầu không hợp lệ'): JsonResponse
    {
        return $this->setStatusCode(self::STATUS_CODE_BAD_REQUEST)->respondWithError($message);
    }

    public function respondCreated($data, $message = 'Tạo thành công'): JsonResponse
    {
        return $this->setStatusCode(self::STATUS_CODE_CREATED)->respondWithData($data, $message);
    }
}
