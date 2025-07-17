<?php

namespace App\Application\Home\Queries;

use App\Domain\Home\Interfaces\Repositories\HomeRepositoryInterface;

/**
 * Query para obter estatísticas da página inicial
 * Segue padrão CQRS para operações de leitura
 */
class GetHomePageStatsQuery
{
    public function __construct(
        private readonly HomeRepositoryInterface $homeRepository
    ) {}

    public function __invoke(): array
    {
        return $this->homeRepository->getPageStats();
    }
} 