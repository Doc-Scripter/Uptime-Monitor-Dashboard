<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;

class Monitor extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'url',
        'type',
        'interval',
        'is_active',
        'status',
        'uptime_percentage',
        'current_latency',
        'tags',
        'last_checked_at',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'is_active' => 'boolean',
        'uptime_percentage' => 'decimal:2',
        'current_latency' => 'integer',
        'interval' => 'integer',
        'tags' => 'array',
        'last_checked_at' => 'datetime',
    ];

    /**
     * Get the health checks for the monitor.
     */
    public function healthChecks(): HasMany
    {
        return $this->hasMany(HealthCheck::class);
    }

    /**
     * Get the latest health check.
     */
    public function latestHealthCheck()
    {
        return $this->hasOne(HealthCheck::class)->latestOfMany('checked_at');
    }

    /**
     * Get recent health checks (last 24 hours).
     */
    public function recentHealthChecks(): HasMany
    {
        return $this->healthChecks()
            ->where('checked_at', '>=', Carbon::now()->subDay())
            ->orderBy('checked_at', 'desc');
    }

    /**
     * Scope to get only active monitors.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope to filter by status.
     */
    public function scopeByStatus($query, string $status)
    {
        return $query->where('status', $status);
    }

    /**
     * Scope to filter by type.
     */
    public function scopeByType($query, string $type)
    {
        return $query->where('type', $type);
    }

    /**
     * Get monitors due for checking.
     */
    public function scopeDueForCheck($query)
    {
        return $query->where('is_active', true)
            ->where(function ($q) {
                $q->whereNull('last_checked_at')
                    ->orWhereRaw('last_checked_at <= NOW() - INTERVAL `interval` MINUTE');
            });
    }

    /**
     * Check if monitor is up.
     */
    public function isUp(): bool
    {
        return $this->status === 'up';
    }

    /**
     * Check if monitor is down.
     */
    public function isDown(): bool
    {
        return $this->status === 'down';
    }

    /**
     * Check if monitor has warning.
     */
    public function hasWarning(): bool
    {
        return $this->status === 'warning';
    }

    /**
     * Mark monitor as checked.
     */
    public function markAsChecked(): void
    {
        $this->last_checked_at = Carbon::now();
        $this->save();
    }

    /**
     * Update monitor status.
     */
    public function updateStatus(string $status, ?int $latency = null): void
    {
        $this->status = $status;
        
        if ($latency !== null) {
            $this->current_latency = $latency;
        }
        
        $this->save();
    }
}
