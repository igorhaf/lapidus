<?php

namespace App\Domain\Home\Actions;

use App\Domain\Home\Interfaces\Repositories\HomeRepositoryInterface;

/**
 * Action para calcular estatísticas da página
 */
class CalculatePageStatsAction
{
    public function __construct(
        private readonly HomeRepositoryInterface $homeRepository
    ) {}

    public function __invoke(): array
    {
        $stats = $this->homeRepository->getPageStats();
        $recentViews = $this->homeRepository->getRecentViews(10);

        return [
            'total_views' => $stats['total_views'] ?? 0,
            'recent_views' => $stats['recent_views'] ?? 0,
            'unique_visitors' => $stats['unique_visitors'] ?? 0,
            'average_session_duration' => $stats['avg_session_duration'] ?? 0,
            'bounce_rate' => $stats['bounce_rate'] ?? 0,
            'last_updated' => now()->toISOString(),
            'recent_views_data' => $recentViews,
        ];
    }
} 