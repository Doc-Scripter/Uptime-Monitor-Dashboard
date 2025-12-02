<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreMonitorRequest;
use App\Http\Requests\UpdateMonitorRequest;
use App\Http\Resources\MonitorResource;
use App\Models\Monitor;
use App\Services\MonitorService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class MonitorController extends Controller
{
    public function __construct(
        private MonitorService $monitorService
    ) {}

    /**
     * Display a listing of monitors.
     */
    public function index(): AnonymousResourceCollection
    {
        $filters = request()->only(['status', 'type', 'is_active']);
        $monitors = $this->monitorService->getAllMonitors($filters);

        return MonitorResource::collection($monitors);
    }

    /**
     * Store a newly created monitor.
     */
    public function store(StoreMonitorRequest $request): JsonResponse
    {
        $monitor = $this->monitorService->createMonitor($request->validated());

        return response()->json([
            'message' => 'Monitor created successfully',
            'data' => new MonitorResource($monitor),
        ], 201);
    }

    /**
     * Display the specified monitor.
     */
    public function show(Monitor $monitor): MonitorResource
    {
        $monitor->load('latestHealthCheck');
        
        return new MonitorResource($monitor);
    }

    /**
     * Update the specified monitor.
     */
    public function update(UpdateMonitorRequest $request, Monitor $monitor): JsonResponse
    {
        $updated = $this->monitorService->updateMonitor($monitor, $request->validated());

        return response()->json([
            'message' => 'Monitor updated successfully',
            'data' => new MonitorResource($updated),
        ]);
    }

    /**
     * Remove the specified monitor.
     */
    public function destroy(Monitor $monitor): JsonResponse
    {
        $this->monitorService->deleteMonitor($monitor);

        return response()->json([
            'message' => 'Monitor deleted successfully',
        ]);
    }
}
