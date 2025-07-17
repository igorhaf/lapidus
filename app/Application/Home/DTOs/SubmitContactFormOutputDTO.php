<?php

namespace App\Application\Home\DTOs;

/**
 * DTO de saída para envio do formulário de contato
 */
class SubmitContactFormOutputDTO
{
    public function __construct(
        public readonly bool $success,
        public readonly string $message,
        public readonly string $contactId,
        public readonly string $timestamp,
        public readonly array $metadata = []
    ) {}

    public function toArray(): array
    {
        return [
            'success' => $this->success,
            'message' => $this->message,
            'contact_id' => $this->contactId,
            'timestamp' => $this->timestamp,
            'metadata' => $this->metadata,
        ];
    }
} 