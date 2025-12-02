<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

class HealthCheck extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'monitor_id',
        'status',
        'response_time',
        'http_code',
        'error_message',
        'checked_at',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'monitor_id' => 'integer',
        'response_time' => 'integer',
        'http_code' => 'integer',
        'checked_at' => 'datetime',
    ];

    /**
     * Get the monitor that owns the health check.
     */
    public function monitor(): BelongsTo
    {
        return $this->belongsTo(Monitor::class);
    }

    /**
     * Scope to get checks for a specific monitor.
     */
    public function scopeForMonitor($query, int $monitorId)
    {
        return $query->where('monitor_id', $monitorId);
    }

    /**
     * Scope to get checks by status.
     */
    public function scopeByStatus($query, string $status)
    {
        return $query->where('status', $status);
    }

    /**
     * Scope to get checks within a date range.
     */
    public function scopeInDateRange($query, Carbon $startDate, Carbon $endDate)
    {
        return $query->whereBetween('checked_at', [$startDate, $endDate]);
    }

    /**
     * Scope to get recent checks.
     */
    public function scopeRecent($query, int $hours = 24)
    {
        return $query->where('checked_at', '>=', Carbon::now()->subHours($hours))
            ->orderBy('checked_at', 'desc');
    }

    /**
     * Scope to get checks from last N days.
     */
    public function scopeLastDays($query, int $days = 7)
    {
        return $query->where('checked_at', '>=', Carbon::now()->subDays($days))
            ->orderBy('checked_at', 'desc');
    }

    /**
     * Check if this check was successful.
     */
    public function wasSuccessful(): bool
    {
        return $this->status === 'up';
    }

    /**
     * Check if this check failed.
     */
    public function failed(): bool
    {
        return $this->status === 'down';
    }

    /**
     * Get formatted response time.
     */
    public function getFormattedResponseTime(): string
    {
        if ($this->response_time === null) {
            return '-';
        }

        return $this->response_time . 'ms';
    }

    /**
     * Get human-readable status.
     */
    public function getStatusLabel(): string
    {
        return match($this->status) {
            'up' => 'Online',
            'down' => 'Offline',
            'warning' => 'Degraded',
            default => 'Unknown',
        };
    }
}
