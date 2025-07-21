<?php

namespace App\Domain\Home\ValueObjects;

/**
 * Value Object para ID de contato
 */
class ContactId
{
    public function __construct(
        private readonly string $value
    ) {
        if (empty($value)) {
            throw new \InvalidArgumentException('Contact ID cannot be empty');
        }
    }

    public function getValue(): string
    {
        return $this->value;
    }

    public function equals(ContactId $other): bool
    {
        return $this->value === $other->value;
    }

    public function __toString(): string
    {
        return $this->value;
    }

    public static function generate(): self
    {
        return new self(uniqid('contact_', true));
    }
} 