<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class HealthCheckResource extends JsonResource
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
            'monitor_id' => $this->monitor_id,
            'status' => $this->status,
            'response_time' => $this->response_time,
            'http_code' => $this->http_code,
            'error_message' => $this->error_message,
            'checked_at' => $this->checked_at->toIso8601String(),
            'created_at' => $this->created_at->toIso8601String(),
            
            // Include monitor name if loaded
            'monitor_name' => $this->whenLoaded('monitor', function () {
                return $this->monitor->name;
            }),
            
            // Status helpers
            'status_label' => $this->getStatusLabel(),
            'was_successful' => $this->wasSuccessful(),
            
            // Formatted values
            'formatted_response_time' => $this->getFormattedResponseTime(),
            'checked_at_relative' => $this->checked_at->diffForHumans(),
        ];
    }
}
