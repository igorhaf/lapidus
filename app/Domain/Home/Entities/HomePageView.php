<?php

namespace App\Domain\Home\Entities;

use App\Domain\Home\ValueObjects\UserId;
use App\Domain\Home\ValueObjects\ViewTimestamp;

/**
 * Entidade que representa uma visualização da página inicial
 */
class HomePageView
{
    public function __construct(
        private readonly UserId $userId,
        private readonly ViewTimestamp $viewedAt,
        private readonly ?string $userIp = null,
        private readonly ?string $userAgent = null
    ) {}

    public function getUserId(): UserId
    {
        return $this->userId;
    }

    public function getViewedAt(): ViewTimestamp
    {
        return $this->viewedAt;
    }

    public function getUserIp(): ?string
    {
        return $this->userIp;
    }

    public function getUserAgent(): ?string
    {
        return $this->userAgent;
    }

    public function isGuest(): bool
    {
        return $this->userId->isGuest();
    }
} 