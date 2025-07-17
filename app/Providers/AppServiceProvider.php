<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use App\ViewComposers\HomePageComposer;
use App\ViewComposers\ContactFormComposer;
use App\Infra\Providers\InfrastructureServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        // Registrar provider de infraestrutura
        $this->app->register(InfrastructureServiceProvider::class);
    }

    public function boot(): void
    {
        // Registrar ViewComposers
        View::composer('home.*', HomePageComposer::class);
        View::composer('*contact*', ContactFormComposer::class);
    }
}
