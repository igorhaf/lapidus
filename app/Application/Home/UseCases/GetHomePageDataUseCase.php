<?php

namespace App\Application\Home\UseCases;

use App\Application\Home\DTOs\GetHomePageDataInputDTO;
use App\Application\Home\DTOs\GetHomePageDataOutputDTO;
use App\Domain\Home\Interfaces\Repositories\HomeRepositoryInterface;
use App\Domain\Home\Actions\RecordPageViewAction;
use App\Domain\Home\Actions\CalculatePageStatsAction;
use App\Domain\Home\Services\PageAnalyticsService;
use App\Infra\Gateways\AnalyticsServiceGateway;
use App\Infra\Gateways\CacheServiceGateway;

/**
 * UseCase para obter dados da página inicial usando gateways
 */
class GetHomePageDataUseCase
{
    public function __construct(
        private readonly HomeRepositoryInterface $homeRepository,
        private readonly RecordPageViewAction $recordPageViewAction,
        private readonly CalculatePageStatsAction $calculatePageStatsAction,
        private readonly PageAnalyticsService $pageAnalyticsService,
        private readonly AnalyticsServiceGateway $analyticsGateway,
        private readonly CacheServiceGateway $cacheGateway
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

        // Registrar no analytics via gateway (não crítico)
        try {
            $this->analyticsGateway->trackPageView('/home', [
                'user_id' => $input->userId,
                'client_id' => $input->userIp,
                'title' => 'Página Inicial',
            ]);
        } catch (\Exception $e) {
            // Analytics falhou, mas não é crítico - apenas loggar
            \Log::info('Analytics falhou, mas não é crítico', ['error' => $e->getMessage()]);
        }

        // Analisar a visualização
        $analytics = $this->pageAnalyticsService->analyzePageView($pageView);

        // Calcular estatísticas (usando cache via gateway)
        $stats = $this->cacheGateway->getContactStats() ?? $this->calculatePageStatsAction->__invoke();

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
                'cached' => $this->cacheGateway->getContactStats() !== null,
            ]
        );
    }
} 