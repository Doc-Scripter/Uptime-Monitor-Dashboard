<?php

use App\Http\Controllers\Api\MonitorController;
use App\Http\Controllers\Api\StatsController;
use App\Http\Controllers\Api\HealthCheckController;
use App\Http\Controllers\Api\NetworkIntelligenceController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

// Monitor management
Route::apiResource('monitors', MonitorController::class);

// Statistics
Route::get('stats/dashboard', [StatsController::class, 'dashboard']);
Route::get('stats/monitor/{monitor}', [StatsController::class, 'monitor']);

// Health checks
Route::get('monitors/{monitor}/health-checks', [HealthCheckController::class, 'index']);
Route::get('monitors/{monitor}/timeline', [HealthCheckController::class, 'timeline']);
Route::get('health-checks/recent', [HealthCheckController::class, 'recent']);

// Network intelligence
Route::get('network/info', [NetworkIntelligenceController::class, 'info']);
