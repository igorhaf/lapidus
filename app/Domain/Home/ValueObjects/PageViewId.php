<?php

namespace App\Domain\Home\ValueObjects;

/**
 * Value Object para ID de visualização de página
 */
class PageViewId
{
    public function __construct(
        private readonly string $value
    ) {
        if (empty($value)) {
            throw new \InvalidArgumentException('Page view ID cannot be empty');
        }
    }

    public function getValue(): string
    {
        return $this->value;
    }

    public function equals(PageViewId $other): bool
    {
        return $this->value === $other->value;
    }

    public function __toString(): string
    {
        return $this->value;
    }

    public static function generate(): self
    {
        return new self(uniqid('view_', true));
    }
} 