<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Domain\Home\Interfaces\Repositories\HomeRepositoryInterface;
use App\Infra\Repositories\Eloquent\Home\EloquentHomeRepository;

class RepositoryServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(
            HomeRepositoryInterface::class,
            EloquentHomeRepository::class
        );
    }
}