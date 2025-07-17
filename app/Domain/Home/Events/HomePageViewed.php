<?php
namespace App\Domain\Home\Events;

class HomePageViewed {
    public function __construct(
        public int $userId,
        public \DateTimeImmutable $viewedAt
    ) {}
}
