<?php

namespace App\Domain\Home\ValueObjects;

/**
 * Value Object para ID do usuário
 */
class UserId
{
    public function __construct(
        private readonly int $value
    ) {
        if ($value < 0) {
            throw new \InvalidArgumentException('User ID must be non-negative');
        }
    }

    public function getValue(): int
    {
        return $this->value;
    }

    public function isGuest(): bool
    {
        return $this->value === 0;
    }

    public function equals(UserId $other): bool
    {
        return $this->value === $other->value;
    }

    public function __toString(): string
    {
        return (string) $this->value;
    }
} 