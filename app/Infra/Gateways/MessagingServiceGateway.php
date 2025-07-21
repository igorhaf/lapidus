<?php

namespace App\Infra\Gateways;

use App\Domain\Home\ValueObjects\Phone;
use Illuminate\Support\Facades\Log;

/**
 * Gateway para serviço de mensagens (SMS/WhatsApp)
 */
class MessagingServiceGateway extends BaseGateway
{
    private const TTL_MESSAGE_STATUS = 60; // 1 minuto

    protected function initializeConfig(): void
    {
        $this->baseUrl = config('services.messaging.base_url', 'https://api.twilio.com/2010-04-01/');
        $this->headers = [
            'Authorization' => 'Basic ' . base64_encode(
                config('services.messaging.account_sid') . ':' . config('services.messaging.auth_token')
            ),
        ];
    }

    /**
     * Enviar SMS
     */
    public function sendSMS(Phone $to, string $message): array
    {
        $data = [
            'To' => $to->getValue(),
            'From' => config('services.messaging.from_number'),
            'Body' => $message,
        ];

        try {
            $response = $this->makeRequest('post', 'Accounts/' . config('services.messaging.account_sid') . '/Messages.json', $data);
            
            Log::info('SMS sent successfully', [
                'to' => $to->getValue(),
                'message' => $message,
                'response' => $response,
            ]);

            return $response;

        } catch (\Exception $e) {
            Log::error('Failed to send SMS', [
                'to' => $to->getValue(),
                'message' => $message,
                'error' => $e->getMessage(),
            ]);

            throw $e;
        }
    }

    /**
     * Enviar WhatsApp
     */
    public function sendWhatsApp(Phone $to, string $message): array
    {
        $data = [
            'To' => 'whatsapp:' . $to->getValue(),
            'From' => 'whatsapp:' . config('services.messaging.whatsapp_number'),
            'Body' => $message,
        ];

        return $this->makeRequest('post', 'Accounts/' . config('services.messaging.account_sid') . '/Messages.json', $data);
    }

    /**
     * Verificar status da mensagem
     */
    public function getMessageStatus(string $messageId): array
    {
        return $this->cache("message_status_{$messageId}", function () use ($messageId) {
            return $this->makeRequest('get', "Accounts/" . config('services.messaging.account_sid') . "/Messages/{$messageId}.json");
        }, self::TTL_MESSAGE_STATUS);
    }
} 