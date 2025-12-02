<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Exception;

class NetworkIntelligenceService
{
    /**
     * ipinfo.io API endpoint.
     */
    private const API_URL = 'https://ipinfo.io';

    /**
     * Cache TTL in seconds (5 minutes).
     */
    private const CACHE_TTL = 300;

    /**
     * Get network information for a given IP address.
     */
    public function getClientInfo(?string $ip = null): array
    {
        // Use client IP if not provided
        if(!$ip) {
            $ip = request()->ip();
        }

        // Check cache first
        $cacheKey = "network_info_{$ip}";
        
        if (Cache::has($cacheKey)) {
            return Cache::get($cacheKey);
        }

        try {
            $info = $this->fetchFromApi($ip);
            
            // Cache the result
            Cache::put($cacheKey, $info, self::CACHE_TTL);
            
            return $info;
        } catch (Exception $e) {
            Log::error("Failed to fetch network info for IP {$ip}: " . $e->getMessage());
            return $this->getFallbackInfo($ip);
        }
    }

    /**
     * Fetch information from ipinfo.io API.
     */
    private function fetchFromApi(string $ip): array
    {
        $url = self::API_URL . "/{$ip}/json";

        $response = Http::timeout(5)->get($url);

        if (!$response->successful()) {
            throw new Exception("API request failed with status: " . $response->status());
        }

        return $this->parseResponse($response->json());
    }

    /**
     * Parse API response into structured format.
     */
    private function parseResponse(array $data): array
    {
        return [
            'ip' => $data['ip'] ?? null,
            'hostname' => $data['hostname'] ?? null,
            'city' => $data['city'] ?? null,
            'region' => $data['region'] ?? null,
            'country' => $data['country'] ?? null,
            'location' => $data['loc'] ?? null, // lat,lng
            'organization' => $data['org'] ?? null, // ISP/ASN
            'postal' => $data['postal'] ?? null,
            'timezone' => $data['timezone'] ?? null,
        ];
    }

    /**
     * Get fallback information when API fails.
     */
    private function getFallbackInfo(string $ip): array
    {
        return [
            'ip' => $ip,
            'hostname' => null,
            'city' => null,
            'region' => null,
            'country' => null,
            'location' => null,
            'organization' => 'Unknown',
            'postal' => null,
            'timezone' => null,
            'error' => 'Unable to fetch network information',
        ];
    }

    /**
     * Clear cache for a specific IP.
     */
    public function clearCache(string $ip): void
    {
        Cache::forget("network_info_{$ip}");
    }

    /**
     * Get formatted location string.
     */
    public function getFormattedLocation(array $info): string
    {
        $parts = array_filter([
            $info['city'] ?? null,
            $info['region'] ?? null,
            $info['country'] ?? null,
        ]);

        return implode(', ', $parts) ?: 'Unknown';
    }
}
