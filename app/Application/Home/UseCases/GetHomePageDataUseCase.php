<?php

namespace App\Application\Home\UseCases;

use App\Application\Home\DTOs\GetHomePageDataInputDTO;
use App\Application\Home\DTOs\GetHomePageDataOutputDTO;
use App\Domain\Home\Interfaces\Repositories\HomeRepositoryInterface;
use App\Domain\Home\Actions\RecordPageViewAction;
use App\Domain\Home\Actions\CalculatePageStatsAction;
use App\Domain\Home\Services\PageAnalyticsService;

/**
 * UseCase para obter dados da página inicial
 * Usa Actions e Services da camada Domain
 */
class GetHomePageDataUseCase
{
    public function __construct(
        private readonly HomeRepositoryInterface $homeRepository,
        private readonly RecordPageViewAction $recordPageViewAction,
        private readonly CalculatePageStatsAction $calculatePageStatsAction,
        private readonly PageAnalyticsService $pageAnalyticsService
    ) {}

    public function __invoke(GetHomePageDataInputDTO $input): GetHomePageDataOutputDTO
    {
        // Usar Action para registrar visualização
        $pageView = $this->recordPageViewAction->__invoke(
            $input->userId ?? 0,
            $input->userIp,
            $input->userAgent
        );

        // Salvar no repositório
        $this->homeRepository->savePageView($pageView);

        // Analisar a visualização
        $analytics = $this->pageAnalyticsService->analyzePageView($pageView);

        // Calcular estatísticas
        $stats = $this->calculatePageStatsAction->__invoke();

        // Retornar DTO de saída
        return new GetHomePageDataOutputDTO(
            message: 'Página inicial carregada com sucesso',
            module: 'home',
            timestamp: $pageView->getViewedAt()->format(),
            user: $pageView->isGuest() ? null : [
                'id' => $pageView->getUserId()->getValue(),
                'is_guest' => false
            ],
            metadata: [
                'analytics' => $analytics,
                'stats' => $stats,
                'page_view_id' => $pageView->getId()->getValue(),
            ]
        );
    }
} 