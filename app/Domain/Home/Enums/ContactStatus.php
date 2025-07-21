<?php

namespace App\Domain\Home\Enums;

/**
 * Enum para status de contato
 */
enum ContactStatus: string
{
    case PENDING = 'pending';
    case READ = 'read';
    case RESPONDED = 'responded';
    case CLOSED = 'closed';
    case SPAM = 'spam';

    public function getLabel(): string
    {
        return match($this) {
            self::PENDING => 'Pendente',
            self::READ => 'Lido',
            self::RESPONDED => 'Respondido',
            self::CLOSED => 'Fechado',
            self::SPAM => 'Spam',
        };
    }

    public function isActive(): bool
    {
        return in_array($this, [self::PENDING, self::READ]);
    }

    public function canRespond(): bool
    {
        return in_array($this, [self::PENDING, self::READ]);
    }
} 