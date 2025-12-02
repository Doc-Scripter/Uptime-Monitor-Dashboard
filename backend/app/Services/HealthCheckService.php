<?php

namespace App\Services;

use App\Models\Monitor;
use App\Models\HealthCheck;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
use Exception;

class HealthCheckService
{
    /**
     * HTTP request timeout in seconds.
     */
    private const TIMEOUT = 10;

    /**
     * Latency threshold for warning status (in ms).
     */
    private const WARNING_THRESHOLD = 500;

    /**
     * Perform health check for a monitor.
     */
    public function performHealthCheck(Monitor $monitor): array
    {
        $startTime = microtime(true);
        $status = 'down';
        $responseTime = null;
        $httpCode = null;
        $errorMessage = null;

        try {
            $response = Http::timeout(self::TIMEOUT)
                ->withOptions(['verify' => false]) // Skip SSL verification for now
                ->get($monitor->url);

            $endTime = microtime(true);
            $responseTime = (int) (($endTime - $startTime) * 1000);
            $httpCode = $response->status();

            // Determine status based on response
            if ($response->successful()) {
                $status = $responseTime > self::WARNING_THRESHOLD ? 'warning' : 'up';
            } else {
                $status = 'down';
                $errorMessage = "HTTP {$httpCode} error";
            }

        } catch (Exception $e) {
            $endTime = microtime(true);
            $responseTime = (int) (($endTime - $startTime) * 1000);
            $status = 'down';
            $errorMessage = $this->sanitizeErrorMessage($e->getMessage());

            Log::warning("Health check failed for monitor {$monitor->id}: {$errorMessage}");
        }

        return [
            'status' => $status,
            'response_time' => $responseTime,
            'http_code' => $httpCode,
            'error_message' => $errorMessage,
        ];
    }

    /**
     * Record health check result in database.
     */
    public function recordHealthCheck(Monitor $monitor, array $result): HealthCheck
    {
        $healthCheck = HealthCheck::create([
            'monitor_id' => $monitor->id,
            'status' => $result['status'],
            'response_time' => $result['response_time'],
            'http_code' => $result['http_code'],
            'error_message' => $result['error_message'],
            'checked_at' => Carbon::now(),
        ]);

        return $healthCheck;
    }

    /**
     * Update monitor status based on health check.
     */
    public function updateMonitorStatus(Monitor $monitor, string $status, ?int $latency = null): void
    {
        $monitor->updateStatus($status, $latency);
        $monitor->markAsChecked();
    }

    /**
     * Perform complete health check (check + record + update).
     */
    public function checkAndRecord(Monitor $monitor): HealthCheck
    {
        $result = $this->performHealthCheck($monitor);
        $healthCheck = $this->recordHealthCheck($monitor, $result);
        $this->updateMonitorStatus($monitor, $result['status'], $result['response_time']);

        return $healthCheck;
    }

    /**
     * Get recent health checks for a monitor.
     */
    public function getRecentChecks(Monitor $monitor, int $hours = 24)
    {
        return $monitor->healthChecks()
            ->recent($hours)
            ->get();
    }

    /**
     * Get checks for timeline display (last N days).
     */
    public function getTimelineData(Monitor $monitor, int $days = 7)
    {
        return $monitor->healthChecks()
            ->lastDays($days)
            ->orderBy('checked_at', 'asc')
            ->get()
            ->map(function ($check) {
                return [
                    'timestamp' => $check->checked_at->toIso8601String(),
                    'status' => $check->status,
                    'response_time' => $check->response_time,
                ];
            });
    }

    /**
     * Sanitize error message for storage.
     */
    private function sanitizeErrorMessage(string $message): string
    {
        // Truncate long messages
        if (strlen($message) > 500) {
            $message = substr($message, 0, 497) . '...';
        }

        return $message;
    }

    /**
     * Calculate uptime percentage from checks.
     */
    public function calculateUptimePercentage(Monitor $monitor, int $days = 7): float
    {
        $checks = $monitor->healthChecks()
            ->lastDays($days)
            ->get();

        if ($checks->isEmpty()) {
            return 100.00;
        }

        $upCount = $checks->where('status', 'up')->count();
        $totalCount = $checks->count();

        return round(($upCount / $totalCount) * 100, 2);
    }
}
