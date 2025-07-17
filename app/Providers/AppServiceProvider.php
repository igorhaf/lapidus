<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use App\ViewComposers\HomePageComposer;
use App\ViewComposers\ContactFormComposer;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        // Registrar ViewComposers
        View::composer('home.*', HomePageComposer::class);
        View::composer('*contact*', ContactFormComposer::class);
    }
}
