<?php

namespace App\Infra\Providers;

use Illuminate\Support\ServiceProvider;
use App\Domain\Home\Interfaces\Repositories\HomeRepositoryInterface;
use App\Domain\Home\Interfaces\Repositories\ContactRepositoryInterface;
use App\Infra\Repositories\Eloquent\Home\EloquentHomeRepository;
use App\Infra\Repositories\Eloquent\Home\EloquentContactRepository;

/**
 * Provider para configuração de repositórios
 */
class RepositoryServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        // Bindings de repositórios
        $this->app->bind(
            HomeRepositoryInterface::class,
            EloquentHomeRepository::class
        );

        $this->app->bind(
            ContactRepositoryInterface::class,
            EloquentContactRepository::class
        );

        // Configurar cache para repositórios
        $this->app->singleton('repository.cache', function ($app) {
            return [
                'enabled' => config('cache.default') !== 'array',
                'ttl' => [
                    'default' => 300, // 5 minutos
                    'short' => 60,     // 1 minuto
                    'long' => 3600,    // 1 hora
                ],
                'tags' => [
                    'home' => ['home', 'module'],
                    'contacts' => ['contacts', 'module'],
                    'analytics' => ['analytics', 'external'],
                ],
            ];
        });
    }

    public function boot(): void
    {
        // Configurar configurações de repositório
        config([
            'repository.pagination' => [
                'default_per_page' => 20,
                'max_per_page' => 100,
            ],
            'repository.cache' => [
                'enabled' => true,
                'default_ttl' => 300,
            ],
        ]);
    }
} 