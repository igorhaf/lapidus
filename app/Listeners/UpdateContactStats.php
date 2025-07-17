<?php

namespace App\Listeners;

use App\Domain\Home\Events\ContactSubmitted;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Cache;

/**
 * Listener para atualizar estatísticas de contatos
 */
class UpdateContactStats implements ShouldQueue
{
    use InteractsWithQueue;

    public function handle(ContactSubmitted $event): void
    {
        // Atualizar cache de estatísticas
        Cache::forget('contact_stats');
        
        // Incrementar contador de contatos
        $stats = Cache::remember('contact_stats', 3600, function () {
            return [
                'total_contacts' => 0,
                'urgent_contacts' => 0,
                'last_updated' => now()->toISOString(),
            ];
        });

        $stats['total_contacts']++;
        if ($event->isUrgent) {
            $stats['urgent_contacts']++;
        }

        Cache::put('contact_stats', $stats, 3600);
    }
} 