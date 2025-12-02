<?php

namespace App\Services;

use App\Models\Monitor;
use Exception;

class MonitorService
{
    public function __construct(
        private HealthCheckService $healthCheckService
    ) {}

    /**
     * Create a new monitor.
     */
    public function createMonitor(array $data): Monitor
    {
        $monitor = Monitor::create([
            'name' => $data['name'],
            'url' => $data['url'],
            'type' => $data['type'] ?? 'website',
            'interval' => $data['interval'] ?? 5,
            'is_active' => $data['is_active'] ?? true,
            'tags' => $data['tags'] ?? [],
            'status' => 'up', // Default status
        ]);

        // Perform initial health check
        if ($monitor->is_active) {
            try {
                $this->healthCheckService->checkAndRecord($monitor);
            } catch (Exception $e) {
                // Log but don't fail creation
                \Log::warning("Initial health check failed for new monitor {$monitor->id}");
            }
        }

        return $monitor->fresh();
    }

    /**
     * Update an existing monitor.
     */
    public function updateMonitor(Monitor $monitor, array $data): Monitor
    {
        $wasInactive = !$monitor->is_active;

        $monitor->update($data);

        // If monitor was just activated, perform immediate check
        if ($wasInactive && $monitor->is_active) {
            try {
                $this->healthCheckService->checkAndRecord($monitor);
            } catch (Exception $e) {
                \Log::warning("Health check failed after activating monitor {$monitor->id}");
            }
        }

        return $monitor->fresh();
    }

    /**
     * Delete a monitor.
     */
    public function deleteMonitor(Monitor $monitor): bool
    {
        return $monitor->delete();
    }

    /**
     * Activate a monitor.
     */
    public function activateMonitor(Monitor $monitor): Monitor
    {
        $monitor->update(['is_active' => true]);

        // Perform immediate check
        try {
            $this->healthCheckService->checkAndRecord($monitor);
        } catch (Exception $e) {
            \Log::warning("Health check failed after activating monitor {$monitor->id}");
        }

        return $monitor->fresh();
    }

    /**
     * Deactivate a monitor.
     */
    public function deactivateMonitor(Monitor $monitor): Monitor
    {
        $monitor->update(['is_active' => false]);
        return $monitor->fresh();
    }

    /**
     * Get all monitors with their latest health check.
     */
    public function getAllMonitors(array $filters = [])
    {
        $query = Monitor::with('latestHealthCheck');

        if (isset($filters['status'])) {
            $query->byStatus($filters['status']);
        }

        if (isset($filters['type'])) {
            $query->byType($filters['type']);
        }

        if (isset($filters['is_active'])) {
            if ($filters['is_active']) {
                $query->active();
            } else {
                $query->where('is_active', false);
            }
        }

        return $query->orderBy('name')->get();
    }

    /**
     * Get monitors that are due for checking.
     */
    public function getMonitorsDueForCheck()
    {
        return Monitor::dueForCheck()->get();
    }
}
