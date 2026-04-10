<?php

namespace App\Exceptions\Handlers;

use App\Exceptions\AllKeysNotAvailableException;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\QueryException;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\ConflictHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\TooManyRequestsHttpException;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;
use Throwable;

class ExceptionHandler
{
    public function handle(Throwable $e): \Illuminate\Http\JsonResponse
    {
//        if ($this->isSeriousException($e)){
//            app('sentry')->captureException($e);
//        }
        return $this->handleApiException($e);
    }

    private function handleApiException(Throwable $e): \Illuminate\Http\JsonResponse
    {
        file_put_contents(storage_path('logs/exceptions_'.date('Y-m-d').'.log'), print_r([
            'time' => now()->format('Y-m-d H:i:s'),
            'file' =>  $e->getFile(),
            'trace' => $e->getTraceAsString()
        ], true), FILE_APPEND);

        if ($e instanceof TooManyRequestsHttpException) {
            return response()->json([
                'message' => $e->getMessage() ?: 'Too many requests, please try again later.'
            ], 429);
        }

        if ($e instanceof AllKeysNotAvailableException) {
            return $this->jsonResponse('Server đang quá tải, xin vui lòng thử lại', 503, $e);
        }

        if (
            $e instanceof AuthorizationException
            || $e instanceof AccessDeniedHttpException
        ) {
            return $this->jsonResponse('Forbidden', 403, $e);
        }

        if (
            $e instanceof AuthenticationException
            || $e instanceof UnauthorizedHttpException
        ) {
            return $this->jsonResponse('Unauthenticated', 401, $e);
        }

        if ($e instanceof NotFoundHttpException) {
            return $this->jsonResponse('Not Found', 404, $e);
        }

        if ($e instanceof \DomainException ||
            $e instanceof ConflictHttpException
        ) {
            return $this->jsonResponse($e->getMessage(), 409, $e);
        }

        if ($e instanceof \ErrorException) {
            Log::emergency('Critical system error', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            return $this->jsonResponse('Critical System Error', 500, $e);
        }
        if ($e instanceof ValidationException){
            if (!config('app.debug')){
                file_put_contents(storage_path('logs/validation.txt'), print_r([
                    'time' => now()->toDateTimeString(),
                    'message' => $e->getMessage(),
                    'trace' => $e->getTraceAsString(),
                ], true));
            }
            return $this->jsonResponse($e->getMessage(), 400, $e);
        }

        if ($e instanceof \UnexpectedValueException
            || $e instanceof \InvalidArgumentException
        ) {
            return $this->jsonResponse($e->getMessage(), 400, $e);
        }

        if (
            $e instanceof HttpResponseException
        ) {
            return $this->jsonResponse($e->getMessage(), 429, $e);
        }

        $statusCode = 500;
        Log::critical('Internal server error', [
            'message' => $e->getMessage(),
            'trace' => $e->getTrace(),
        ]);
        return $this->jsonResponse('Internal Server Error', $statusCode, $e);
    }

    private function jsonResponse(string $message, int $statusCode, Throwable $e): \Illuminate\Http\JsonResponse
    {
        $data = ['message' => $message];

        if (config('app.debug') && $e->getMessage()) {
            $data['error'] = $e->getMessage();
            $data['trace'] = $e->getTrace();
        }

        return response()->json($data, $statusCode);
    }

    private function isSeriousException(\Throwable $e): bool
    {
        // Định nghĩa logic của bạn để xác định lỗi nghiêm trọng
        return $e instanceof \Error ||
            $e instanceof \ErrorException ||
            $e instanceof \RuntimeException ||
            $e->getCode() >= 500 ||
            $e->getPrevious() instanceof \Error ||
            in_array(get_class($e), [
                QueryException::class,
            ]) ||
            stripos(get_class($e), 'fatal') !== false ||
            // Hoặc kiểm tra theo context
            app()->environment('production');
    }
}
