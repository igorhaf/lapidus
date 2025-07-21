<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Domain\Foo\Repositories\FooRepositoryInterface;
use App\Infra\Repositories\Eloquent\FooRepository;

class RepositoryServiceProvider extends ServiceProvider {
    public function register() {
        $this->app->bind(
            FooRepositoryInterface::class,
            FooRepository::class
        );
    }
}