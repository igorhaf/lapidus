<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
// Exemplo: use App\Domain\Home\Interfaces\Repositories\HomeRepositoryInterface;
// Exemplo: use App\Infra\Repositories\Eloquent\HomeRepository;

class RepositoryServiceProvider extends ServiceProvider {
    public function register() {
        // Exemplo de binding real:
        // $this->app->bind(
        //     HomeRepositoryInterface::class,
        //     HomeRepository::class
        // );
    }
}