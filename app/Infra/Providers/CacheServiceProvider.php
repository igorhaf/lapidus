<?php

namespace App\Infra\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Cache;

/**
 * Provider para configuração de cache
 */
class CacheServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        // Configurar cache customizado para módulos
        $this->app->singleton('cache.modules', function ($app) {
            return [
                'home' => Cache::tags(['home', 'module']),
                'contacts' => Cache::tags(['contacts', 'module']),
                'analytics' => Cache::tags(['analytics', 'external']),
                'user_sessions' => Cache::tags(['user', 'session']),
            ];
        });

        // Configurar TTL por tipo de dados
        $this->app->singleton('cache.ttl', function ($app) {
            return [
                'page_stats' => 300,      // 5 minutos
                'user_data' => 1800,      // 30 minutos
                'analytics' => 3600,      // 1 hora
                'sessions' => 86400,      // 24 horas
                'static_data' => 604800,  // 1 semana
            ];
        });
    }

    public function boot(): void
    {
        // Configurar configurações de cache
        config([
            'cache.prefix' => [
                'home' => 'home:',
                'contacts' => 'contacts:',
                'analytics' => 'analytics:',
                'sessions' => 'session:',
            ],
        ]);
    }
} 