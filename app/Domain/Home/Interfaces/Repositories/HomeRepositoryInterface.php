<?php

namespace App\Domain\Home\Interfaces\Repositories;

use App\Domain\Home\Entities\HomePageView;

/**
 * Contrato para repositório de Home
 */
interface HomeRepositoryInterface
{
    /**
     * Salva uma visualização da página inicial
     */
    public function savePageView(HomePageView $pageView): void;

    /**
     * Busca estatísticas da página inicial
     */
    public function getPageStats(): array;

    /**
     * Busca visualizações recentes
     */
    public function getRecentViews(int $limit = 10): array;
} 