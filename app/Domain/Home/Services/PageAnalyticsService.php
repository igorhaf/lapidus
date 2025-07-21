<?php

namespace App\Domain\Home\Services;

use App\Domain\Home\Entities\HomePageView;
use App\Domain\Home\ValueObjects\UserAgent;
use App\Domain\Home\Enums\UserType;

/**
 * Service para análise de páginas
 */
class PageAnalyticsService
{
    /**
     * Analisa uma visualização de página
     */
    public function analyzePageView(HomePageView $pageView): array
    {
        $userAgent = new UserAgent($pageView->getUserAgent() ?? '');
        
        return [
            'is_bot' => $userAgent->isBot(),
            'is_mobile' => $userAgent->isMobile(),
            'user_type' => $this->determineUserType($pageView),
            'session_quality' => $this->calculateSessionQuality($pageView),
        ];
    }

    /**
     * Determina o tipo de usuário
     */
    private function determineUserType(HomePageView $pageView): UserType
    {
        if ($pageView->isGuest()) {
            return UserType::GUEST;
        }

        // Em uma implementação real, verificaria no banco
        return UserType::REGISTERED;
    }

    /**
     * Calcula a qualidade da sessão
     */
    private function calculateSessionQuality(HomePageView $pageView): float
    {
        $score = 1.0;

        // Penalizar bots
        $userAgent = new UserAgent($pageView->getUserAgent() ?? '');
        if ($userAgent->isBot()) {
            $score *= 0.1;
        }

        // Bonus para usuários registrados
        if (!$pageView->isGuest()) {
            $score *= 1.5;
        }

        return min($score, 1.0);
    }
} 