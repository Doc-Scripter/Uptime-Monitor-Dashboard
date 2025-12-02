<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\HealthCheckResource;
use App\Models\Monitor;
use App\Models\HealthCheck;
use App\Services\HealthCheckService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class HealthCheckController extends Controller
{
    public function __construct(
        private HealthCheckService $healthCheckService
    ) {}

    /**
     * Get health check history for a monitor.
     */
    public function index(Monitor $monitor): AnonymousResourceCollection
    {
        $days = request()->input('days', 7);
        
        $checks = $monitor->healthChecks()
            ->lastDays($days)
            ->get();

        return HealthCheckResource::collection($checks);
    }

    /**
     * Get timeline data for charts.
     */
    public function timeline(Monitor $monitor): JsonResponse
    {
        $days = request()->input('days', 7);
        $timeline = $this->healthCheckService->getTimelineData($monitor, $days);

        return response()->json($timeline);
    }

    /**
     * Get recent health events across all monitors.
     */
    public function recent(): AnonymousResourceCollection
    {
        $hours = request()->input('hours', 24);
        
        $checks = HealthCheck::with('monitor')
            ->recent($hours)
            ->limit(50)
            ->get();

        return HealthCheckResource::collection($checks);
    }
}
