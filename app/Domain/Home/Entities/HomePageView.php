<?php

namespace App\Domain\Home\Entities;

use App\Domain\Home\ValueObjects\UserId;
use App\Domain\Home\ValueObjects\ViewTimestamp;
use App\Domain\Home\ValueObjects\PageViewId;
use App\Domain\Home\Traits\Trackable;

/**
 * Entidade que representa uma visualização da página inicial
 */
class HomePageView
{
    use Trackable;

    public function __construct(
        private readonly PageViewId $id,
        private readonly UserId $userId,
        private readonly ViewTimestamp $viewedAt,
        private readonly ?string $userIp = null,
        private readonly ?string $userAgent = null
    ) {
        $this->setCreatedAt($viewedAt);
        $this->setUpdatedAt($viewedAt);
    }

    public function getId(): PageViewId
    {
        return $this->id;
    }

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

    public static function create(
        int $userId,
        ?string $userIp = null,
        ?string $userAgent = null
    ): self {
        return new self(
            id: PageViewId::generate(),
            userId: new UserId($userId),
            viewedAt: new ViewTimestamp(new \DateTimeImmutable()),
            userIp: $userIp,
            userAgent: $userAgent
        );
    }
} 