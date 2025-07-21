<?php

namespace App\Application\Home\DTOs;

/**
 * DTO de entrada para envio do formulário de contato
 */
class SubmitContactFormInputDTO
{
    public function __construct(
        public readonly string $name,
        public readonly string $email,
        public readonly ?string $phone,
        public readonly string $subject,
        public readonly string $message,
        public readonly ?string $preferredContact,
        public readonly bool $newsletter,
        public readonly ?string $userIp = null,
        public readonly ?string $userAgent = null
    ) {}

    public static function fromRequest(array $data): self
    {
        return new self(
            name: $data['name'],
            email: $data['email'],
            phone: $data['phone'] ?? null,
            subject: $data['subject'],
            message: $data['message'],
            preferredContact: $data['preferred_contact'] ?? null,
            newsletter: $data['newsletter'] ?? false,
            userIp: $data['user_ip'] ?? null,
            userAgent: $data['user_agent'] ?? null
        );
    }
}
