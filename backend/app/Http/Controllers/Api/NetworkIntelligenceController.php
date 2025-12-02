<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\NetworkIntelligenceService;
use Illuminate\Http\JsonResponse;

class NetworkIntelligenceController extends Controller
{
    public function __construct(
        private NetworkIntelligenceService $networkService
    ) {}

    /**
     * Get client network information.
     */
    public function info(): JsonResponse
    {
        $ip = request()->input('ip') ?? request()->ip();
        $info = $this->networkService->getClientInfo($ip);

        return response()->json($info);
    }
}
