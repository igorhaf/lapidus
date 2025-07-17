<?php

namespace App\Domain\Home\Events;

/**
 * Evento disparado quando um contato é enviado
 */
class ContactSubmitted
{
    public function __construct(
        public readonly string $contactId,
        public readonly string $email,
        public readonly bool $isUrgent
    ) {}
} 