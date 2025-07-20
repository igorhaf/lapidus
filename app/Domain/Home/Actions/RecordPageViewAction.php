<?php

namespace App\Domain\Home\Actions;

use App\Domain\Home\Entities\HomePageView;
use App\Domain\Home\ValueObjects\UserId;
use App\Domain\Home\ValueObjects\PageViewId;
use App\Domain\Home\ValueObjects\ViewTimestamp;
use App\Domain\Home\ValueObjects\UserAgent;
use App\Domain\Home\Events\HomePageViewed;
use Illuminate\Support\Facades\Event;

/**
 * Action para registrar visualização de página
 */
class RecordPageViewAction
{
    public function __invoke(
        int $userId,
        ?string $userIp = null,
        ?string $userAgent = null
    ): HomePageView {
        // Criar entidade de visualização
        $pageView = new HomePageView(
            id: new PageViewId(\Str::uuid()->toString()),
            userId: new UserId($userId),
            viewedAt: new ViewTimestamp(new \DateTimeImmutable()),
            userIp: $userIp,
            userAgent: $userAgent
        );

        // Disparar evento de domínio
        Event::dispatch(new HomePageViewed(
            $pageView->getUserId()->getValue(),
            $pageView->getViewedAt()->getValue()
        ));

        return $pageView;
    }
} 