<?php

namespace App\Infra\Repositories\Eloquent\Home;

use App\Domain\Home\Interfaces\Repositories\ContactRepositoryInterface;
use App\Domain\Home\Entities\Contact;
use App\Domain\Home\ValueObjects\ContactId;
use App\Domain\Home\Enums\ContactStatus;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;

/**
 * Implementação Eloquent do repositório de contatos
 */
class EloquentContactRepository implements ContactRepositoryInterface
{
    public function save(Contact $contact): void
    {
        // Por enquanto, vamos logar e simular salvamento
        // Em uma implementação real, salvaria no banco
        \Log::info('Contact saved', [
            'id' => $contact->getId()->getValue(),
            'email' => $contact->getEmail()->getValue(),
            'name' => $contact->getName(),
            'is_urgent' => $contact->isUrgent(),
        ]);

        // Invalidar cache
        Cache::forget('contacts_list');
        Cache::forget('urgent_contacts');
    }

    public function findById(ContactId $id): ?Contact
    {
        // Simular busca por ID
        return null; // Implementação real retornaria a entidade
    }

    public function list(array $filters = [], int $limit = 20): array
    {
        return Cache::remember('contacts_list', 300, function () use ($filters, $limit) {
            // Simular lista de contatos
            return [
                'contacts' => [],
                'total' => 0,
                'filters' => $filters,
            ];
        });
    }

    public function findUrgent(): array
    {
        return Cache::remember('urgent_contacts', 60, function () {
            // Simular contatos urgentes
            return [
                'urgent_contacts' => [],
                'count' => 0,
            ];
        });
    }

    public function updateStatus(ContactId $id, string $status): void
    {
        \Log::info('Contact status updated', [
            'id' => $id->getValue(),
            'status' => $status,
        ]);

        // Invalidar cache
        Cache::forget('contacts_list');
    }
} 