<?php

namespace App\Infra\Gateways;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;

/**
 * Gateway para serviços de e-mail
 */
class EmailServiceGateway extends BaseGateway
{
    protected function initializeConfig(): void
    {
        $this->baseUrl = config('services.email.base_url', 'https://api.example-email.com');
        $this->headers = [
            'Authorization' => 'Bearer ' . config('services.email.api_key'),
            'Content-Type' => 'application/json',
            'Accept' => 'application/json',
        ];
        $this->timeout = config('services.email.timeout', 30);
        $this->retries = config('services.email.retries', 3);
    }

    /**
     * Enviar e-mail
     */
    public function sendEmail(array $emailData): array
    {
        try {
            return $this->makeRequest('post', '/send', $emailData);
        } catch (\Exception $e) {
            Log::error('Erro ao enviar email', [
                'error' => $e->getMessage(),
                'email_data' => $emailData
            ]);
            throw $e;
        }
    }

    /**
     * Verificar status de envio
     */
    public function getStatus(string $messageId): array
    {
        return $this->cache("status:{$messageId}", function () use ($messageId) {
            return $this->makeRequest('get', "/status/{$messageId}");
        }, 60);
    }

    /**
     * Listar templates disponíveis
     */
    public function getTemplates(): array
    {
        return $this->cache('templates', function () {
            return $this->makeRequest('get', '/templates');
        }, 3600);
    }

    /**
     * Enviar notificação de novo contato
     */
    public function sendContactNotification(array $contactData): array
    {
        $emailData = [
            'to' => config('mail.admin_email', 'admin@example.com'),
            'subject' => 'Novo contato recebido',
            'template' => 'contact_notification',
            'variables' => $contactData
        ];

        return $this->sendEmail($emailData);
    }
} 