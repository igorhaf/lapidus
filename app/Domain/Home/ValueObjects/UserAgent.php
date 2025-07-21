<?php

namespace App\Domain\Home\ValueObjects;

/**
 * Value Object para User Agent
 */
class UserAgent
{
    public function __construct(
        private readonly string $value
    ) {
        if (strlen($value) > 500) {
            throw new \InvalidArgumentException('User agent too long');
        }
    }

    public function getValue(): string
    {
        return $this->value;
    }

    public function isBot(): bool
    {
        $botPatterns = ['bot', 'crawler', 'spider', 'scraper'];
        $lowerValue = strtolower($this->value);
        
        foreach ($botPatterns as $pattern) {
            if (str_contains($lowerValue, $pattern)) {
                return true;
            }
        }
        
        return false;
    }

    public function isMobile(): bool
    {
        $mobilePatterns = ['mobile', 'android', 'iphone', 'ipad'];
        $lowerValue = strtolower($this->value);
        
        foreach ($mobilePatterns as $pattern) {
            if (str_contains($lowerValue, $pattern)) {
                return true;
            }
        }
        
        return false;
    }
} 