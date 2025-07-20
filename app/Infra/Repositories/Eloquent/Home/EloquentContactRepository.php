<?php

namespace App\Infra\Repositories\Eloquent\Home;

use App\Domain\Home\Interfaces\Repositories\ContactRepositoryInterface;
use App\Domain\Home\Entities\Contact;
use App\Domain\Home\ValueObjects\ContactId;
use App\Domain\Home\Enums\ContactStatus;
use App\Models\Contact as EloquentContact;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;

/**
 * Implementação Eloquent do repositório de contatos
 */
class EloquentContactRepository implements ContactRepositoryInterface
{
    public function save(Contact $contact): void
    {
        try {
            // Salvar no banco usando Model Eloquent
            EloquentContact::create([
                'contact_id' => $contact->getId()->getValue(),
                'name' => $contact->getName(),
                'email' => $contact->getEmail()->getValue(),
                'phone' => $contact->getPhone()?->getValue(),
                'subject' => $contact->getSubject(),
                'message' => $contact->getMessage(),
                'preferred_contact' => $contact->getPreferredContact(),
                'newsletter' => $contact->wantsNewsletter(),
                'status' => $contact->getStatus(),
                'user_ip' => $contact->getUserIp(),
                'user_agent' => $contact->getUserAgent(),
            ]);

            // Log para auditoria
            \Log::info('Contact saved successfully', [
                'id' => $contact->getId()->getValue(),
                'email' => $contact->getEmail()->getValue(),
                'name' => $contact->getName(),
                'is_urgent' => $contact->isUrgent(),
            ]);

            // Invalidar cache
            Cache::forget('contacts_list');
            Cache::forget('urgent_contacts');

        } catch (\Exception $e) {
            \Log::error('Error saving contact', [
                'error' => $e->getMessage(),
                'contact_id' => $contact->getId()->getValue(),
                'email' => $contact->getEmail()->getValue(),
            ]);
            
            throw $e;
        }
    }

    public function findById(ContactId $id): ?Contact
    {
        $eloquentContact = EloquentContact::where('contact_id', $id->getValue())->first();
        
        if (!$eloquentContact) {
            return null;
        }

        // Converter Model Eloquent para Domain Entity
        return $this->toDomainEntity($eloquentContact);
    }

    public function list(array $filters = [], int $limit = 20): array
    {
        $cacheKey = 'contacts_list_' . md5(serialize($filters) . $limit);
        
        return Cache::remember($cacheKey, 300, function () use ($filters, $limit) {
            $query = EloquentContact::query();

            // Aplicar filtros
            if (isset($filters['status'])) {
                $query->where('status', $filters['status']);
            }
            
            if (isset($filters['urgent']) && $filters['urgent']) {
                $query->urgent();
            }
            
            if (isset($filters['newsletter']) && $filters['newsletter']) {
                $query->withNewsletter();
            }

            $contacts = $query->orderBy('created_at', 'desc')
                           ->limit($limit)
                           ->get();

            $total = $query->count();

            return [
                'contacts' => $contacts->map(fn($contact) => $this->toDomainEntity($contact))->toArray(),
                'total' => $total,
                'filters' => $filters,
            ];
        });
    }

    public function findUrgent(): array
    {
        return Cache::remember('urgent_contacts', 60, function () {
            $urgentContacts = EloquentContact::urgent()
                                          ->pending()
                                          ->orderBy('created_at', 'desc')
                                          ->limit(10)
                                          ->get();

            return [
                'urgent_contacts' => $urgentContacts->map(fn($contact) => $this->toDomainEntity($contact))->toArray(),
                'count' => $urgentContacts->count(),
            ];
        });
    }

    public function updateStatus(ContactId $id, string $status): void
    {
        try {
            $updated = EloquentContact::where('contact_id', $id->getValue())
                                   ->update(['status' => $status]);

            if ($updated) {
                \Log::info('Contact status updated successfully', [
                    'id' => $id->getValue(),
                    'status' => $status,
                ]);
            } else {
                \Log::warning('Contact not found for status update', [
                    'id' => $id->getValue(),
                    'status' => $status,
                ]);
            }

            // Invalidar cache
            Cache::forget('contacts_list');
            Cache::forget('urgent_contacts');

        } catch (\Exception $e) {
            \Log::error('Error updating contact status', [
                'error' => $e->getMessage(),
                'id' => $id->getValue(),
                'status' => $status,
            ]);
            
            throw $e;
        }
    }

    /**
     * Converte Model Eloquent para Domain Entity
     */
    private function toDomainEntity(EloquentContact $eloquentContact): Contact
    {
        return new Contact(
            id: new ContactId($eloquentContact->contact_id),
            name: $eloquentContact->name,
            email: new \App\Domain\Home\ValueObjects\Email($eloquentContact->email),
            phone: $eloquentContact->phone ? new \App\Domain\Home\ValueObjects\Phone($eloquentContact->phone) : null,
            subject: $eloquentContact->subject,
            message: $eloquentContact->message,
            preferredContact: $eloquentContact->preferred_contact,
            newsletter: $eloquentContact->newsletter,
            status: $eloquentContact->status,
            userIp: $eloquentContact->user_ip,
            userAgent: $eloquentContact->user_agent
        );
    }
} 