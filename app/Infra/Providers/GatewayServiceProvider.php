<?php

namespace App\Infra\Providers;

use Illuminate\Support\ServiceProvider;
use App\Infra\Gateways\EmailServiceGateway;
use App\Infra\Gateways\MessagingServiceGateway;
use App\Infra\Gateways\AnalyticsServiceGateway;
use App\Infra\Gateways\CacheServiceGateway;

/**
 * Provider para configuração de gateways
 */
class GatewayServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        // Registrar gateways como singletons
        $this->app->singleton(EmailServiceGateway::class, function ($app) {
            return new EmailServiceGateway();
        });

        $this->app->singleton(MessagingServiceGateway::class, function ($app) {
            return new MessagingServiceGateway();
        });

        $this->app->singleton(AnalyticsServiceGateway::class, function ($app) {
            return new AnalyticsServiceGateway();
        });

        $this->app->singleton(CacheServiceGateway::class, function ($app) {
            return new CacheServiceGateway();
        });

        // Configurar gateways de forma condicional
        $this->app->singleton('gateways.config', function ($app) {
            return [
                'email' => [
                    'enabled' => config('services.email.enabled', true),
                    'provider' => config('services.email.provider', 'sendgrid'),
                    'fallback' => config('services.email.fallback', 'log'),
                ],
                'messaging' => [
                    'enabled' => config('services.messaging.enabled', true),
                    'provider' => config('services.messaging.provider', 'twilio'),
                    'fallback' => config('services.messaging.fallback', 'log'),
                ],
                'analytics' => [
                    'enabled' => config('services.analytics.enabled', true),
                    'provider' => config('services.analytics.provider', 'google'),
                ],
                'cache' => [
                    'enabled' => config('cache.default') !== 'array',
                    'provider' => config('cache.default', 'redis'),
                ],
            ];
        });
    }

    public function boot(): void
    {
        // Configurar configurações de gateway
        config([
            'gateways.retry' => [
                'max_attempts' => 3,
                'backoff_multiplier' => 2,
                'max_delay' => 60,
            ],
            'gateways.timeout' => [
                'default' => 30,
                'email' => 60,
                'messaging' => 15,
                'analytics' => 45,
            ],
        ]);
    }
} 