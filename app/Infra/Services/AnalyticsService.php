<?php

namespace App\Infra\Services;

use App\Domain\Home\Interfaces\Services\PageAnalyticsServiceInterface;
use App\Infra\Gateways\AnalyticsServiceGateway;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

/**
 * Serviço de analytics implementando interface do domínio
 */
class AnalyticsService implements PageAnalyticsServiceInterface
{
    public function __construct(
        private AnalyticsServiceGateway $analyticsGateway
    ) {}

    public function recordPageView(int $userId, string $pageType, array $metadata = []): bool
    {
        try {
            $data = [
                'user_id' => $userId,
                'page_type' => $pageType,
                'timestamp' => now()->toISOString(),
                'metadata' => $metadata,
                'session_id' => session()->getId(),
                'ip_address' => request()->ip(),
                'user_agent' => request()->userAgent()
            ];

            // Enviar para gateway de analytics
            $response = $this->analyticsGateway->trackPageView($data);

            // Cache local para performance
            $this->cachePageView($data);

            Log::info('Page view recorded', $data);

            return $response['success'] ?? false;
        } catch (\Exception $e) {
            Log::error('Failed to record page view', [
                'error' => $e->getMessage(),
                'data' => $data ?? []
            ]);
            return false;
        }
    }

    public function getPageStats(string $pageType, int $days = 30): array
    {
        $cacheKey = "page_stats_{$pageType}_{$days}";

        return Cache::remember($cacheKey, 3600, function () use ($pageType, $days) {
            try {
                $stats = $this->analyticsGateway->getPageStats($pageType, $days);
                
                return [
                    'total_views' => $stats['total_views'] ?? 0,
                    'unique_visitors' => $stats['unique_visitors'] ?? 0,
                    'avg_time_on_page' => $stats['avg_time_on_page'] ?? 0,
                    'bounce_rate' => $stats['bounce_rate'] ?? 0,
                    'conversion_rate' => $stats['conversion_rate'] ?? 0
                ];
            } catch (\Exception $e) {
                Log::error('Failed to get page stats', [
                    'error' => $e->getMessage(),
                    'page_type' => $pageType
                ]);
                
                return [
                    'total_views' => 0,
                    'unique_visitors' => 0,
                    'avg_time_on_page' => 0,
                    'bounce_rate' => 0,
                    'conversion_rate' => 0
                ];
            }
        });
    }

    public function trackUserBehavior(int $userId, string $action, array $data = []): bool
    {
        try {
            $eventData = [
                'user_id' => $userId,
                'action' => $action,
                'timestamp' => now()->toISOString(),
                'data' => $data,
                'session_id' => session()->getId()
            ];

            $response = $this->analyticsGateway->trackEvent($eventData);

            Log::info('User behavior tracked', $eventData);

            return $response['success'] ?? false;
        } catch (\Exception $e) {
            Log::error('Failed to track user behavior', [
                'error' => $e->getMessage(),
                'data' => $eventData ?? []
            ]);
            return false;
        }
    }

    private function cachePageView(array $data): void
    {
        $key = "recent_page_views_" . date('Y-m-d');
        $views = Cache::get($key, []);
        $views[] = $data;
        
        // Manter apenas os últimos 1000 registros
        if (count($views) > 1000) {
            $views = array_slice($views, -1000);
        }
        
        Cache::put($key, $views, 86400); // 24 horas
    }
} 