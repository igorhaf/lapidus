<?php

namespace App\Domain\Home\Interfaces\Services;

use App\Domain\Home\Entities\HomePageView;

/**
 * Interface para serviço de análise de páginas
 */
interface PageAnalyticsServiceInterface
{
    /**
     * Analisa uma visualização de página
     */
    public function analyzePageView(HomePageView $pageView): array;

    /**
     * Calcula métricas de engajamento
     */
    public function calculateEngagementMetrics(array $views): array;
} 