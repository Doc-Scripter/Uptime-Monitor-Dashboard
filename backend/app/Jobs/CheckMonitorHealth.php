<?php

namespace App\Jobs;

use App\Models\Monitor;
use App\Services\HealthCheckService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Exception;

class CheckMonitorHealth implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * The monitor instance.
     */
    public Monitor $monitor;

    /**
     * The number of times the job may be attempted.
     */
    public int $tries = 3;

    /**
     * The number of seconds to wait before retrying.
     */
    public int $backoff = 60;

    /**
     * Create a new job instance.
     */
    public function __construct(Monitor $monitor)
    {
        $this->monitor = $monitor;
    }

    /**
     * Execute the job.
     */
    public function handle(HealthCheckService $healthCheckService): void
    {
        try {
            $healthCheckService->checkAndRecord($this->monitor);
            
            Log::info("Health check completed for monitor: {$this->monitor->name}");
        } catch (Exception $e) {
            Log::error(
                "Health check job failed for monitor {$this->monitor->id}: {$e->getMessage()}"
            );
            
            // Re-throw to trigger retry mechanism
            throw $e;
        }
    }

    /**
     * Handle a job failure.
     */
    public function failed(Exception $exception): void
    {
        Log::error(
            "Health check job permanently failed for monitor {$this->monitor->id} after {$this->tries} attempts"
        );
    }
}
