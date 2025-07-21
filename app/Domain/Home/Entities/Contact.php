<?php

namespace App\Domain\Home\Entities;

use App\Domain\Home\ValueObjects\ContactId;
use App\Domain\Home\ValueObjects\Email;
use App\Domain\Home\ValueObjects\Phone;
use App\Domain\Home\Enums\ContactStatus;
use App\Domain\Home\Traits\Trackable;

/**
 * Entidade que representa um contato
 */
class Contact
{
    use Trackable;

    public function __construct(
        private readonly ContactId $id,
        private readonly string $name,
        private readonly Email $email,
        private readonly ?Phone $phone,
        private readonly string $subject,
        private readonly string $message,
        private readonly ?string $preferredContact,
        private readonly bool $newsletter,
        private readonly ContactStatus $status = ContactStatus::PENDING,
        private readonly ?string $userIp = null,
        private readonly ?string $userAgent = null
    ) {}

    public function getId(): ContactId
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getEmail(): Email
    {
        return $this->email;
    }

    public function getPhone(): ?Phone
    {
        return $this->phone;
    }

    public function getSubject(): string
    {
        return $this->subject;
    }

    public function getMessage(): string
    {
        return $this->message;
    }

    public function getPreferredContact(): ?string
    {
        return $this->preferredContact;
    }

    public function wantsNewsletter(): bool
    {
        return $this->newsletter;
    }

    public function getStatus(): ContactStatus
    {
        return $this->status;
    }

    public function getUserIp(): ?string
    {
        return $this->userIp;
    }

    public function getUserAgent(): ?string
    {
        return $this->userAgent;
    }

    public function markAsRead(): void
    {
        // Em uma implementação real, isso mudaria o status
    }

    public function isUrgent(): bool
    {
        $urgentKeywords = ['urgente', 'emergência', 'critico', 'crítico', 'critica', 'crítica', 'problema'];
        $message = strtolower($this->message);
        
        foreach ($urgentKeywords as $keyword) {
            if (str_contains($message, $keyword)) {
                return true;
            }
        }
        
        return false;
    }
} 