<?php

namespace App\Domain\Home\Traits;

use App\Domain\Home\ValueObjects\ViewTimestamp;

/**
 * Trait para entidades que podem ser rastreadas
 */
trait Trackable
{
    private ?ViewTimestamp $createdAt = null;
    private ?ViewTimestamp $updatedAt = null;

    public function setCreatedAt(ViewTimestamp $timestamp): void
    {
        $this->createdAt = $timestamp;
    }

    public function setUpdatedAt(ViewTimestamp $timestamp): void
    {
        $this->updatedAt = $timestamp;
    }

    public function getCreatedAt(): ?ViewTimestamp
    {
        return $this->createdAt;
    }

    public function getUpdatedAt(): ?ViewTimestamp
    {
        return $this->updatedAt;
    }

    public function isRecentlyCreated(int $minutes = 5): bool
    {
        return $this->createdAt && $this->createdAt->isRecent($minutes);
    }
} 