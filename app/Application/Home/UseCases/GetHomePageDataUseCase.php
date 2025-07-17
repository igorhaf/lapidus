<?php

namespace App\Application\Home\UseCases;

use App\Application\Home\DTOs\GetHomePageDataInputDTO;
use App\Application\Home\DTOs\GetHomePageDataOutputDTO;
use App\Domain\Home\Interfaces\Repositories\HomeRepositoryInterface;
use App\Domain\Home\Entities\HomePageView;
use App\Domain\Home\ValueObjects\UserId;
use App\Domain\Home\ValueObjects\ViewTimestamp;
use App\Domain\Home\Events\HomePageViewed;
use Illuminate\Support\Facades\Event;

/**
 * UseCase para obter dados da página inicial
 * Segue padrões de Clean Architecture
 */
class GetHomePageDataUseCase
{
    public function __construct(
        private readonly HomeRepositoryInterface $homeRepository
    ) {}

    public function __invoke(GetHomePageDataInputDTO $input): GetHomePageDataOutputDTO
    {
        // Criar entidade de domínio
        $pageView = new HomePageView(
            userId: new UserId($input->userId ?? 0),
            viewedAt: new ViewTimestamp(new \DateTimeImmutable()),
            userIp: $input->userIp,
            userAgent: $input->userAgent
        );

        // Salvar no repositório
        $this->homeRepository->savePageView($pageView);

        // Disparar evento de domínio
        Event::dispatch(new HomePageViewed(
            $pageView->getUserId()->getValue(),
            $pageView->getViewedAt()->getValue()
        ));

        // Obter estatísticas
        $stats = $this->homeRepository->getPageStats();

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
                'total_views' => $stats['total_views'] ?? 0,
                'recent_views' => $stats['recent_views'] ?? 0,
                'user_ip' => $pageView->getUserIp(),
            ]
        );
    }
} 