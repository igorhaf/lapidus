<?php

namespace App\Infra\Providers;

use Illuminate\Support\ServiceProvider;

/**
 * Provider para configuração de serviços externos
 */
class ExternalServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        // Configurar serviços de e-mail
        $this->app->singleton('services.email', function ($app) {
            return [
                'provider' => config('services.email.provider', 'sendgrid'),
                'enabled' => config('services.email.enabled', true),
                'templates' => [
                    'contact_notification' => config('services.email.templates.contact_notification'),
                    'urgent_contact' => config('services.email.templates.urgent_contact'),
                    'welcome' => config('services.email.templates.welcome'),
                ],
                'rate_limits' => [
                    'per_minute' => 60,
                    'per_hour' => 1000,
                    'per_day' => 10000,
                ],
            ];
        });

        // Configurar serviços de mensagens
        $this->app->singleton('services.messaging', function ($app) {
            return [
                'provider' => config('services.messaging.provider', 'twilio'),
                'enabled' => config('services.messaging.enabled', true),
                'templates' => [
                    'sms' => [
                        'contact_notification' => 'Novo contato: {contact_id}',
                        'urgent_contact' => '�� CONTATO URGENTE: {contact_id}',
                    ],
                    'whatsapp' => [
                        'contact_notification' => 'contact_notification',
                        'urgent_contact' => 'urgent_contact_alert',
                    ],
                ],
                'rate_limits' => [
                    'sms_per_minute' => 10,
                    'whatsapp_per_minute' => 5,
                ],
            ];
        });

        // Configurar serviços de analytics
        $this->app->singleton('services.analytics', function ($app) {
            return [
                'provider' => config('services.analytics.provider', 'google'),
                'enabled' => config('services.analytics.enabled', true),
                'tracking_id' => config('services.analytics.tracking_id'),
                'view_id' => config('services.analytics.view_id'),
                'events' => [
                    'page_view' => ['category' => 'Page', 'action' => 'View'],
                    'contact_submit' => ['category' => 'Contact', 'action' => 'Submit'],
                    'user_login' => ['category' => 'User', 'action' => 'Login'],
                ],
            ];
        });
    }

    public function boot(): void
    {
        // Configurar configurações de serviços externos
        config([
            'services.external' => [
                'timeout' => 30,
                'retry_attempts' => 3,
                'circuit_breaker' => [
                    'enabled' => true,
                    'threshold' => 5,
                    'timeout' => 60,
                ],
            ],
        ]);
    }
} 