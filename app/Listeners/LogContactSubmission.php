<?php

namespace App\Listeners;

use App\Domain\Home\Events\ContactSubmitted;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;

/**
 * Listener para logar submissão de contato
 */
class LogContactSubmission implements ShouldQueue
{
    use InteractsWithQueue;

    public function handle(ContactSubmitted $event): void
    {
        Log::info('Contact submitted', [
            'contact_id' => $event->contactId,
            'email' => $event->email,
            'is_urgent' => $event->isUrgent,
            'timestamp' => now()->toISOString(),
        ]);
    }
} 