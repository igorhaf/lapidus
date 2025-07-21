<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Domain\Home\Interfaces\Repositories\HomeRepositoryInterface;
use App\Domain\Home\Interfaces\Repositories\ContactRepositoryInterface;
use App\Domain\Home\Interfaces\Services\PageAnalyticsServiceInterface;
use App\Infra\Repositories\Eloquent\Home\EloquentHomeRepository;
use App\Infra\Repositories\Eloquent\Home\EloquentContactRepository;
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

        $this->app->bind(
            ContactRepositoryInterface::class,
            EloquentContactRepository::class
        );

        // Services
        $this->app->bind(
            PageAnalyticsServiceInterface::class,
            PageAnalyticsService::class
        );
    }
}
