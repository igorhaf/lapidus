<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Infra\Gateways\EmailServiceGateway;
use App\Infra\Gateways\MessagingServiceGateway;
use App\Infra\Gateways\AnalyticsServiceGateway;
use App\Infra\Gateways\CacheServiceGateway;

class GatewayServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        // Registrar gateways como singletons
        $this->app->singleton(EmailServiceGateway::class);
        $this->app->singleton(MessagingServiceGateway::class);
        $this->app->singleton(AnalyticsServiceGateway::class);
        $this->app->singleton(CacheServiceGateway::class);
    }
} 