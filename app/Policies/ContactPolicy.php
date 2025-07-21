<?php

namespace App\Policies;

use App\Models\User;
use App\Domain\Home\Entities\Contact;
use App\Domain\Home\Enums\ContactStatus;

/**
 * Policy para autorização de contatos
 */
class ContactPolicy
{
    /**
     * Determina se o usuário pode visualizar contatos
     */
    public function viewAny(?User $user): bool
    {
        return $user !== null; // Por enquanto, qualquer usuário autenticado
    }

    /**
     * Determina se o usuário pode visualizar um contato específico
     */
    public function view(?User $user, Contact $contact): bool
    {
        return $user !== null && 
               $user->email === $contact->getEmail()->getValue();
    }

    /**
     * Determina se o usuário pode criar contatos
     */
    public function create(?User $user): bool
    {
        return true; // Qualquer pessoa (incluindo guests) pode enviar contato
    }

    /**
     * Determina se o usuário pode atualizar um contato
     */
    public function update(?User $user, Contact $contact): bool
    {
        return $user !== null; // Por enquanto, qualquer usuário autenticado
    }

    /**
     * Determina se o usuário pode deletar um contato
     */
    public function delete(?User $user, Contact $contact): bool
    {
        return $user !== null; // Por enquanto, qualquer usuário autenticado
    }

    /**
     * Determina se o usuário pode marcar contato como respondido
     */
    public function respond(?User $user, Contact $contact): bool
    {
        return $user !== null && 
               $contact->getStatus() === ContactStatus::PENDING;
    }

    /**
     * Determina se o usuário pode marcar contato como spam
     */
    public function markAsSpam(?User $user, Contact $contact): bool
    {
        return $user !== null; // Por enquanto, qualquer usuário autenticado
    }
} 