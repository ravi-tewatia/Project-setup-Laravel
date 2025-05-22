<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;

class BaseController extends Controller
{
    /**
     * Success response method.
     *
     * @param  mixed  $result
     * @param  string  $message
     * @param  int  $code
     * @return JsonResponse
     */
    protected function sendResponse($result, string $message, int $code = Response::HTTP_OK): JsonResponse
    {
        $response = [
            'success' => true,
            'data'    => $result,
            'message' => $message,
        ];

        return response()->json($response, $code);
    }

    /**
     * Error response method.
     *
     * @param  string  $error
     * @param  array  $errorMessages
     * @param  int  $code
     * @return JsonResponse
     */
    protected function sendError(string $error, array $errorMessages = [], int $code = Response::HTTP_BAD_REQUEST): JsonResponse
    {
        $response = [
            'success' => false,
            'message' => $error,
        ];

        if (!empty($errorMessages)) {
            $response['data'] = $errorMessages;
        }

        return response()->json($response, $code);
    }

    /**
     * Not found response method.
     *
     * @param  string  $message
     * @return JsonResponse
     */
    protected function sendNotFound(string $message = 'Resource not found'): JsonResponse
    {
        return $this->sendError($message, [], Response::HTTP_NOT_FOUND);
    }

    /**
     * Unauthorized response method.
     *
     * @param  string  $message
     * @return JsonResponse
     */
    protected function sendUnauthorized(string $message = 'Unauthorized'): JsonResponse
    {
        return $this->sendError($message, [], Response::HTTP_UNAUTHORIZED);
    }

    /**
     * Forbidden response method.
     *
     * @param  string  $message
     * @return JsonResponse
     */
    protected function sendForbidden(string $message = 'Forbidden'): JsonResponse
    {
        return $this->sendError($message, [], Response::HTTP_FORBIDDEN);
    }

    /**
     * Validation error response method.
     *
     * @param  array  $errors
     * @return JsonResponse
     */
    protected function sendValidationError(array $errors): JsonResponse
    {
        return $this->sendError('Validation Error', $errors, Response::HTTP_UNPROCESSABLE_ENTITY);
    }

    /**
     * Pagination response method.
     *
     * @param  array  $data
     * @param  array  $pagination
     * @param  string  $message
     * @param  int  $code
     * @return JsonResponse
     */
    public function sendPaginatedResponse($data, $pagination, $message = 'Success', $code = 200): JsonResponse
    {
        $response = [
            'status' => 'success',
            'code' => $code,
            'message' => $message,
            'data' => $data,
            'meta' => [
                'pagination' => $pagination,
                'timestamp' => now()->toIso8601String(),
            ]
        ];

        return response()->json($response, $code);
    }
} 