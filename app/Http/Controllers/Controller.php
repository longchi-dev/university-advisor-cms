<?php

namespace App\Http\Controllers;

use Illuminate\Routing\Controller as BaseController;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class Controller extends BaseController
{
    public function sendSuccessResponse($data = [], $code = 200, $success = true, $msg = 'Success'): JsonResponse
    {
        return response()->json([
            'code' => $code,
            'success' => $success,
            'data' => $data,
            'msg' => $msg,
            'time' => time()
        ], $code);
    }

    public function sendErrorResponse($error = '', $code = 400): JsonResponse
    {
        $data = [
            'code'    => $code,
            'success' => false,
            'msg'     => $error,
            'data'    => null,
            'time'    => time()
        ];
        return response()->json($data, $code);
    }

    public function sendResponseNoUpdate(): JsonResponse
    {
        return response()->json([], Response::HTTP_NO_CONTENT);
    }

    public function sendResponseAccepted($data = []): JsonResponse
    {
        return response()->json(['data' => $data], Response::HTTP_ACCEPTED);
    }

    public function sendSuccessPaginateResponse(
        ?LengthAwarePaginator $paginator = null, $data = [], int $code = 200, bool $success = true, string $msg = 'Success'
    ): JsonResponse
    {
        return response()->json([
            'code' => $code,
            'success' => $success,
            'data' => $data,
            'msg' => $msg,
            'time' => now()->timestamp,
            'current_page' => $paginator?->currentPage() ?: 1,
            'last_page' => $paginator?->lastPage() ?: 1,
            'per_page' => $paginator?->perPage() ?: 15,
            'total' => $paginator?->total() ?: 0,
        ], $code);
    }

    public function sendPaginateResponse(?LengthAwarePaginator $paginator = null): JsonResponse
    {
        return response()->json([
            'code' => 200,
            'success' => true,
            'data' => [
                'data' => $paginator->items(),
                'meta' => [
                    'current_page' => $paginator->currentPage(),
                    'per_page' => $paginator->perPage(),
                    'total' => $paginator->total(),
                ]
            ],
            'time' => now()->timestamp,
        ]);
    }

    protected function isInvalidPagination($perPage, $page): bool
    {
        $maxPerPage = (int) config('app.max_per_page');
        return $perPage < 1 || $perPage > $maxPerPage || $page < 1;
    }
}
