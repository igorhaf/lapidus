<?php

namespace App\Infra\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Log;

/**
 * Provider para configuração de monitoramento
 */
class MonitoringServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        // Configurar métricas de monitoramento
        $this->app->singleton('monitoring.metrics', function ($app) {
            return [
                'performance' => [
                    'response_time' => true,
                    'memory_usage' => true,
                    'database_queries' => true,
                    'cache_hit_rate' => true,
                ],
                'business' => [
                    'contact_submissions' => true,
                    'page_views' => true,
                    'user_sessions' => true,
                    'error_rate' => true,
                ],
                'infrastructure' => [
                    'disk_usage' => true,
                    'cpu_usage' => true,
                    'queue_jobs' => true,
                    'external_services' => true,
                ],
            ];
        });

        // Configurar alertas
        $this->app->singleton('monitoring.alerts', function ($app) {
            return [
                'thresholds' => [
                    'error_rate' => 5,        // 5% de erro
                    'response_time' => 5000,   // 5 segundos
                    'memory_usage' => 80,      // 80% de memória
                    'disk_usage' => 90,        // 90% de disco
                ],
                'channels' => [
                    'email' => config('services.monitoring.email'),
                    'slack' => config('services.monitoring.slack_webhook'),
                    'sms' => config('services.monitoring.sms_number'),
                ],
            ];
        });
    }

    public function boot(): void
    {
        // Configurar logging de monitoramento
        config([
            'logging.channels.monitoring' => [
                'driver' => 'daily',
                'path' => storage_path('logs/monitoring.log'),
                'level' => 'info',
                'days' => 30,
            ],
        ]);

        // Registrar listener para monitoramento
        $this->app['events']->listen('*', function ($eventName, $payload) {
            $this->trackEvent($eventName, $payload);
        });
    }

    private function trackEvent(string $eventName, array $payload): void
    {
        Log::channel('monitoring')->info('Event tracked', [
            'event' => $eventName,
            'payload' => $payload,
            'timestamp' => now()->toISOString(),
        ]);
    }
} 