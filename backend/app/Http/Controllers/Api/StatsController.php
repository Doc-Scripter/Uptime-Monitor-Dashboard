<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\StatsService;
use App\Models\Monitor;
use Illuminate\Http\JsonResponse;

class StatsController extends Controller
{
    public function __construct(
        private StatsService $statsService
    ) {}

    /**
     * Get dashboard statistics.
     */
    public function dashboard(): JsonResponse
    {
        $days = request()->input('days', 7);
        $stats = $this->statsService->getDashboardStats($days);

        return response()->json($stats);
    }

    /**
     * Get statistics for a specific monitor.
     */
    public function monitor(Monitor $monitor): JsonResponse
    {
        $days = request()->input('days', 7);
        $stats = $this->statsService->getMonitorStats($monitor, $days);

        return response()->json($stats);
    }
}
