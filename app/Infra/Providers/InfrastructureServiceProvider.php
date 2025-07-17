<?php

namespace App\Infra\Providers;

use Illuminate\Support\ServiceProvider;

/**
 * Provider principal que registra todos os providers de infraestrutura
 */
class InfrastructureServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        // Registrar providers de infraestrutura
        $this->app->register(RepositoryServiceProvider::class);
        $this->app->register(GatewayServiceProvider::class);
        $this->app->register(CacheServiceProvider::class);
        $this->app->register(ExternalServiceProvider::class);
        $this->app->register(MonitoringServiceProvider::class);

        // Configurar configurações globais de infraestrutura
        $this->app->singleton('infrastructure.config', function ($app) {
            return [
                'enabled' => true,
                'environment' => app()->environment(),
                'debug' => config('app.debug'),
                'version' => '1.0.0',
                'modules' => [
                    'home' => [
                        'enabled' => true,
                        'cache_ttl' => 300,
                        'rate_limit' => 100,
                    ],
                    'contacts' => [
                        'enabled' => true,
                        'cache_ttl' => 1800,
                        'rate_limit' => 10,
                    ],
                ],
            ];
        });
    }

    public function boot(): void
    {
        // Configurações globais
        config([
            'infrastructure.enabled' => true,
            'infrastructure.environment' => app()->environment(),
            'infrastructure.debug' => config('app.debug'),
        ]);

        // Publicar configurações se necessário
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__ . '/../../../config/infrastructure.php' => config_path('infrastructure.php'),
            ], 'infrastructure-config');
        }
    }
} 