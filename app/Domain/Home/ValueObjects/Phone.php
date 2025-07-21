<?php

namespace App\Domain\Home\ValueObjects;

/**
 * Value Object para telefone
 */
class Phone
{
    public function __construct(
        private readonly string $value
    ) {
        $cleanValue = preg_replace('/[^0-9+()-]/', '', $value);
        if (strlen($cleanValue) < 10) {
            throw new \InvalidArgumentException('Phone number too short');
        }
    }

    public function getValue(): string
    {
        return $this->value;
    }

    public function getCleanValue(): string
    {
        return preg_replace('/[^0-9+()-]/', '', $this->value);
    }

    public function isMobile(): bool
    {
        $cleanValue = $this->getCleanValue();
        return str_contains($cleanValue, '9') && strlen($cleanValue) >= 11;
    }

    public function equals(Phone $other): bool
    {
        return $this->getCleanValue() === $other->getCleanValue();
    }

    public function __toString(): string
    {
        return $this->value;
    }
} 