<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MonitorResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'url' => $this->url,
            'type' => $this->type,
            'interval' => $this->interval,
            'is_active' => $this->is_active,
            'status' => $this->status,
            'uptime_percentage' => $this->uptime_percentage 
                ? (float) $this->uptime_percentage 
                : null,
            'current_latency' => $this->current_latency,
            'tags' => $this->tags ?? [],
            'last_checked_at' => $this->last_checked_at?->toIso8601String(),
            'created_at' => $this->created_at->toIso8601String(),
            'updated_at' => $this->updated_at->toIso8601String(),
            
            // Include latest health check if loaded
            'latest_health_check' => new HealthCheckResource(
                $this->whenLoaded('latestHealthCheck')
            ),
            
            // Status helpers
            'status_label' => match($this->status) {
                'up' => 'Online',
                'down' => 'Offline',
                'warning' => 'Degraded',
                default => 'Unknown',
            },
            
            // Formatted values
            'formatted_latency' => $this->current_latency 
                ? $this->current_latency . 'ms' 
                : '-',
            'last_checked_relative' => $this->last_checked_at 
                ? $this->last_checked_at->diffForHumans() 
                : 'Never',
        ];
    }
}
