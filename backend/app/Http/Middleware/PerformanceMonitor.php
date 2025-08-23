<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;
use Symfony\Component\HttpFoundation\Response;

class PerformanceMonitor
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $startTime = microtime(true);
        $startMemory = memory_get_usage();

        // Process the request
        $response = $next($request);

        // Calculate performance metrics
        $duration = microtime(true) - $startTime;
        $memoryUsed = memory_get_usage() - $startMemory;
        $peakMemory = memory_get_peak_usage();

        // Log performance metrics for slow requests
        if ($duration > 1.0) { // Log requests taking more than 1 second
            Log::warning('Slow API Request', [
                'method' => $request->method(),
                'url' => $request->fullUrl(),
                'duration' => round($duration, 3),
                'memory_used' => $this->formatBytes($memoryUsed),
                'peak_memory' => $this->formatBytes($peakMemory),
                'user_agent' => $request->userAgent(),
                'ip' => $request->ip(),
            ]);
        }

        // Add performance headers
        $response->headers->set('X-Response-Time', round($duration * 1000, 2) . 'ms');
        $response->headers->set('X-Memory-Used', $this->formatBytes($memoryUsed));

        // Cache performance metrics for analytics
        $this->cachePerformanceMetrics($request, $duration, $memoryUsed);

        return $response;
    }

    /**
     * Format bytes to human readable format
     */
    private function formatBytes(int $bytes): string
    {
        $units = ['B', 'KB', 'MB', 'GB'];
        $bytes = max($bytes, 0);
        $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
        $pow = min($pow, count($units) - 1);
        $bytes /= pow(1024, $pow);
        return round($bytes, 2) . ' ' . $units[$pow];
    }

    /**
     * Cache performance metrics for analytics
     */
    private function cachePerformanceMetrics(Request $request, float $duration, int $memoryUsed): void
    {
        $key = 'performance_metrics_' . date('Y-m-d');
        $metrics = Cache::get($key, []);

        $endpoint = $request->method() . ' ' . $request->path();
        
        if (!isset($metrics[$endpoint])) {
            $metrics[$endpoint] = [
                'count' => 0,
                'total_duration' => 0,
                'total_memory' => 0,
                'avg_duration' => 0,
                'avg_memory' => 0,
                'min_duration' => PHP_FLOAT_MAX,
                'max_duration' => 0,
            ];
        }

        $metrics[$endpoint]['count']++;
        $metrics[$endpoint]['total_duration'] += $duration;
        $metrics[$endpoint]['total_memory'] += $memoryUsed;
        $metrics[$endpoint]['avg_duration'] = $metrics[$endpoint]['total_duration'] / $metrics[$endpoint]['count'];
        $metrics[$endpoint]['avg_memory'] = $metrics[$endpoint]['total_memory'] / $metrics[$endpoint]['count'];
        $metrics[$endpoint]['min_duration'] = min($metrics[$endpoint]['min_duration'], $duration);
        $metrics[$endpoint]['max_duration'] = max($metrics[$endpoint]['max_duration'], $duration);

        Cache::put($key, $metrics, 86400); // Cache for 24 hours
    }
}
