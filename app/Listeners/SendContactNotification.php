<?php

namespace App\Listeners;

use App\Domain\Home\Events\ContactSubmitted;
use App\Infra\Gateways\EmailServiceGateway;
use App\Infra\Gateways\MessagingServiceGateway;
use App\Infra\Gateways\AnalyticsServiceGateway;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;

/**
 * Listener para enviar notificação de contato usando gateways
 */
class SendContactNotification implements ShouldQueue
{
    use InteractsWithQueue;

    public function __construct(
        private readonly EmailServiceGateway $emailGateway,
        private readonly MessagingServiceGateway $messagingGateway,
        private readonly AnalyticsServiceGateway $analyticsGateway
    ) {}

    public function handle(ContactSubmitted $event): void
    {
        try {
            // Enviar e-mail de notificação
            $this->emailGateway->sendContactNotification(
                $event->contactId,
                $event->email,
                $event->isUrgent
            );

            // Se for urgente, enviar SMS também
            if ($event->isUrgent) {
                $this->messagingGateway->sendSMS(
                    new \App\Domain\Home\ValueObjects\Phone(config('services.messaging.admin_phone')),
                    "🚨 CONTATO URGENTE: {$event->contactId}"
                );
            }

            // Registrar evento no analytics
            $this->analyticsGateway->trackContactSubmission($event->contactId, [
                'is_urgent' => $event->isUrgent,
                'email' => $event->email,
            ]);

            Log::info('Contact notification sent via gateways', [
                'contact_id' => $event->contactId,
                'email' => $event->email,
                'is_urgent' => $event->isUrgent,
            ]);

        } catch (\Exception $e) {
            Log::error('Failed to send contact notification via gateways', [
                'contact_id' => $event->contactId,
                'error' => $e->getMessage(),
            ]);
        }
    }
} 