<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;

    /**
     * Cache response with automatic cache key generation
     */
    protected function cachedResponse(string $key, callable $callback, int $ttl = 300): JsonResponse
    {
        return Cache::remember($key, $ttl, $callback);
    }

    /**
     * Clear cache by pattern
     */
    protected function clearCache(string $pattern): void
    {
        $keys = Cache::get($pattern);
        if (is_array($keys)) {
            foreach ($keys as $key) {
                Cache::forget($key);
            }
        }
    }

    /**
     * Optimized success response
     */
    protected function successResponse($data = null, string $message = 'Success', int $code = 200): JsonResponse
    {
        return response()->json([
            'success' => true,
            'message' => $message,
            'data' => $data,
            'timestamp' => now()->toISOString(),
        ], $code);
    }

    /**
     * Optimized error response
     */
    protected function errorResponse(string $message = 'Error', int $code = 400, $errors = null): JsonResponse
    {
        $response = [
            'success' => false,
            'message' => $message,
            'timestamp' => now()->toISOString(),
        ];

        if ($errors) {
            $response['errors'] = $errors;
        }

        return response()->json($response, $code);
    }

    /**
     * Log performance metrics
     */
    protected function logPerformance(string $operation, float $startTime): void
    {
        $duration = microtime(true) - $startTime;
        Log::info("Performance: {$operation} took {$duration}s");
    }

    /**
     * Generate cache key from request parameters
     */
    protected function generateCacheKey(string $prefix, array $params = []): string
    {
        $key = $prefix;
        if (!empty($params)) {
            $key .= '_' . md5(serialize($params));
        }
        return $key;
    }

    /**
     * Optimized pagination response
     */
    protected function paginatedResponse($data, $pagination): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data' => $data,
            'pagination' => [
                'current_page' => $pagination->currentPage(),
                'last_page' => $pagination->lastPage(),
                'per_page' => $pagination->perPage(),
                'total' => $pagination->total(),
                'from' => $pagination->firstItem(),
                'to' => $pagination->lastItem(),
            ],
            'timestamp' => now()->toISOString(),
        ]);
    }
}
