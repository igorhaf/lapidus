<?php

namespace App\Infra\Services;

use App\Infra\Gateways\MonitoringServiceGateway;
use Illuminate\Support\Facades\Log;

/**
 * Serviço de monitoramento para infraestrutura
 */
class MonitoringService
{
    public function __construct(
        private MonitoringServiceGateway $monitoringGateway
    ) {}

    public function trackPerformance(string $operation, float $duration, array $metadata = []): bool
    {
        try {
            $data = [
                'operation' => $operation,
                'duration' => $duration,
                'timestamp' => now()->toISOString(),
                'metadata' => $metadata,
                'memory_usage' => memory_get_usage(true),
                'peak_memory' => memory_get_peak_usage(true)
            ];

            $response = $this->monitoringGateway->trackMetric($data);

            return $response['success'] ?? false;
        } catch (\Exception $e) {
            Log::error('Failed to track performance', [
                'error' => $e->getMessage(),
                'operation' => $operation
            ]);
            return false;
        }
    }

    public function trackError(\Throwable $error, array $context = []): bool
    {
        try {
            $data = [
                'error_type' => get_class($error),
                'message' => $error->getMessage(),
                'file' => $error->getFile(),
                'line' => $error->getLine(),
                'trace' => $error->getTraceAsString(),
                'context' => $context,
                'timestamp' => now()->toISOString()
            ];

            $response = $this->monitoringGateway->trackError($data);

            return $response['success'] ?? false;
        } catch (\Exception $e) {
            Log::error('Failed to track error', [
                'error' => $e->getMessage(),
                'original_error' => $error->getMessage()
            ]);
            return false;
        }
    }

    public function healthCheck(): array
    {
        try {
            $health = $this->monitoringGateway->getHealthStatus();
            
            return [
                'status' => $health['status'] ?? 'unknown',
                'services' => $health['services'] ?? [],
                'timestamp' => now()->toISOString()
            ];
        } catch (\Exception $e) {
            Log::error('Failed to get health status', [
                'error' => $e->getMessage()
            ]);
            
            return [
                'status' => 'error',
                'services' => [],
                'timestamp' => now()->toISOString()
            ];
        }
    }
} 