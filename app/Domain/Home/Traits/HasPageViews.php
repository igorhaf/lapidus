<?php

namespace App\Domain\Home\Traits;

use App\Domain\Home\Entities\HomePageView;

/**
 * Trait para entidades que podem ter visualizações
 */
trait HasPageViews
{
    private array $pageViews = [];

    public function addPageView(HomePageView $pageView): void
    {
        $this->pageViews[] = $pageView;
    }

    public function getPageViews(): array
    {
        return $this->pageViews;
    }

    public function getPageViewCount(): int
    {
        return count($this->pageViews);
    }

    public function getRecentPageViews(int $limit = 10): array
    {
        return array_slice($this->pageViews, -$limit);
    }

    public function hasPageViews(): bool
    {
        return !empty($this->pageViews);
    }
} 