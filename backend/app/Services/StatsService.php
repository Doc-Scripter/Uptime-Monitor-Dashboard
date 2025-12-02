<?php

namespace App\Services;

use App\Models\Monitor;
use App\Models\HealthCheck;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class StatsService
{
    /**
     * Get overall dashboard statistics.
     */
    public function getDashboardStats(int $days = 7): array
    {
        $monitors = Monitor::all();
        $activeMonitors = $monitors->where('is_active', true);

        return [
            'overall_uptime' => $this->getOverallUptime($days),
            'average_latency' => $this->getAverageLatency($days),
            'monitors_count' => [
                'total' => $monitors->count(),
                'active' => $activeMonitors->count(),
                'up' => $monitors->where('status', 'up')->count(),
                'warning' => $monitors->where('status', 'warning')->count(),
                'down' => $monitors->where('status', 'down')->count(),
            ],
            'period_days' => $days,
        ];
    }

    /**
     * Get statistics for a specific monitor.
     */
    public function getMonitorStats(Monitor $monitor, int $days = 7): array
    {
        $startDate = Carbon::now()->subDays($days);

        return [
            'uptime_percentage' => $this->calculateUptime($monitor, $days),
            'average_latency' => $this->getMonitorAverageLatency($monitor, $days),
            'total_checks' => $this->getTotalChecks($monitor, $days),
            'failed_checks' => $this->getFailedChecks($monitor, $days),
            'last_downtime' => $this->getLastDowntime($monitor),
            'period_days' => $days,
        ];
    }

    /**
     * Calculate uptime percentage for a monitor.
     */
    public function calculateUptime(Monitor $monitor, int $days = 7): float
    {
        $startDate = Carbon::now()->subDays($days);

        $totalChecks = HealthCheck::where('monitor_id', $monitor->id)
            ->where('checked_at', '>=', $startDate)
            ->count();

        if ($totalChecks === 0) {
            return 100.00;
        }

        $successfulChecks = HealthCheck::where('monitor_id', $monitor->id)
            ->where('checked_at', '>=', $startDate)
            ->where('status', 'up')
            ->count();

        return round(($successfulChecks / $totalChecks) * 100, 2);
    }

    /**
     * Get overall uptime across all monitors.
     */
    private function getOverallUptime(int $days): float
    {
        $startDate = Carbon::now()->subDays($days);

        $totalChecks = HealthCheck::where('checked_at', '>=', $startDate)->count();

        if ($totalChecks === 0) {
            return 100.00;
        }

        $successfulChecks = HealthCheck::where('checked_at', '>=', $startDate)
            ->where('status', 'up')
            ->count();

        return round(($successfulChecks / $totalChecks) * 100, 2);
    }

    /**
     * Get average latency across all monitors.
     */
    private function getAverageLatency(int $days): ?int
    {
        $startDate = Carbon::now()->subDays($days);

        return (int) HealthCheck::where('checked_at', '>=', $startDate)
            ->whereNotNull('response_time')
            ->avg('response_time');
    }

    /**
     * Get average latency for a specific monitor.
     */
    private function getMonitorAverageLatency(Monitor $monitor, int $days): ?int
    {
        $startDate = Carbon::now()->subDays($days);

        return (int) HealthCheck::where('monitor_id', $monitor->id)
            ->where('checked_at', '>=', $startDate)
            ->whereNotNull('response_time')
            ->avg('response_time');
    }

    /**
     * Get total checks count for a monitor.
     */
    private function getTotalChecks(Monitor $monitor, int $days): int
    {
        $startDate = Carbon::now()->subDays($days);

        return HealthCheck::where('monitor_id', $monitor->id)
            ->where('checked_at', '>=', $startDate)
            ->count();
    }

    /**
     * Get failed checks count for a monitor.
     */
    private function getFailedChecks(Monitor $monitor, int $days): int
    {
        $startDate = Carbon::now()->subDays($days);

        return HealthCheck::where('monitor_id', $monitor->id)
            ->where('checked_at', '>=', $startDate)
            ->where('status', 'down')
            ->count();
    }

    /**
     * Get last downtime occurrence for a monitor.
     */
    private function getLastDowntime(Monitor $monitor): ?string
    {
        $lastDownCheck = HealthCheck::where('monitor_id', $monitor->id)
            ->where('status', 'down')
            ->orderBy('checked_at', 'desc')
            ->first();

        return $lastDownCheck?->checked_at->toIso8601String();
    }

    /**
     * Get status distribution.
     */
    public function getStatusDistribution(): array
    {
        return [
            'up' => Monitor::byStatus('up')->count(),
            'warning' => Monitor::byStatus('warning')->count(),
            'down' => Monitor::byStatus('down')->count(),
        ];
    }
}
