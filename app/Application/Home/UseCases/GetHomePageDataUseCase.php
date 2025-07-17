<?php

namespace App\Application\Home\UseCases;

use App\Domain\Home\Events\HomePageViewed;
use Illuminate\Support\Facades\Event;

/**
 * UseCase para obter dados da página inicial
 */
class GetHomePageDataUseCase
{
    public function execute(?int $userId = null): array
    {
        // Disparar evento de domínio
        Event::dispatch(new HomePageViewed(
            $userId ?? 0,
            new \DateTimeImmutable()
        ));

        return [
            'message' => 'Página inicial carregada com sucesso',
            'module' => 'home',
            'timestamp' => now()->toISOString(),
        ];
    }
} 