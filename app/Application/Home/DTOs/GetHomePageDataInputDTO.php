<?php

namespace App\Application\Home\DTOs;

/**
 * DTO de entrada para obter dados da página inicial
 */
class GetHomePageDataInputDTO
{
    public function __construct(
        public readonly ?int $userId = null,
        public readonly ?string $userIp = null,
        public readonly ?string $userAgent = null
    ) {}

    public static function fromRequest(array $data): self
    {
        return new self(
            userId: $data['user_id'] ?? null,
            userIp: $data['user_ip'] ?? null,
            userAgent: $data['user_agent'] ?? null
        );
    }
} 