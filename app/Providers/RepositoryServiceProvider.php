<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Domain\Home\Interfaces\Repositories\HomeRepositoryInterface;
use App\Domain\Home\Interfaces\Services\PageAnalyticsServiceInterface;
use App\Infra\Repositories\Eloquent\Home\EloquentHomeRepository;
use App\Domain\Home\Services\PageAnalyticsService;

class RepositoryServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        // Repositories
        $this->app->bind(
            HomeRepositoryInterface::class,
            EloquentHomeRepository::class
        );

        // Services
        $this->app->bind(
            PageAnalyticsServiceInterface::class,
            PageAnalyticsService::class
        );
    }
}