<?php

namespace App\Domain\Home\ValueObjects;

/**
 * Value Object para timestamp de visualização
 */
class ViewTimestamp
{
    public function __construct(
        private readonly \DateTimeImmutable $value
    ) {}

    public function getValue(): \DateTimeImmutable
    {
        return $this->value;
    }

    public function isRecent(int $minutes = 5): bool
    {
        $now = new \DateTimeImmutable();
        $diff = $now->diff($this->value);
        return $diff->i < $minutes;
    }

    public function format(string $format = 'c'): string
    {
        return $this->value->format($format);
    }

    public function equals(ViewTimestamp $other): bool
    {
        return $this->value->getTimestamp() === $other->value->getTimestamp();
    }
} 