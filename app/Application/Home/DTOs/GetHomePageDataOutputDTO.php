<?php

namespace App\Application\Home\DTOs;

/**
 * DTO de saída com dados da página inicial
 */
class GetHomePageDataOutputDTO
{
    public function __construct(
        public readonly string $message,
        public readonly string $module,
        public readonly string $timestamp,
        public readonly ?array $user = null,
        public readonly array $metadata = []
    ) {}

    public function toArray(): array
    {
        return [
            'message' => $this->message,
            'module' => $this->module,
            'timestamp' => $this->timestamp,
            'user' => $this->user,
            'metadata' => $this->metadata,
        ];
    }
} 