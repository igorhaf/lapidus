<?php

namespace App\Http\Controllers\Api\V1\Home;

use App\Http\Controllers\Api\V1\Controller as ApiController;
use App\Domain\Home\Events\HomePageViewed;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Event;
use Inertia\Inertia;
use Inertia\Response;

/**
 * Controller para página inicial
 * Segue padrão modular da arquitetura
 */
class HomeController extends ApiController
{
    /**
     * Exibe a página inicial (GET /api/v1/pagina-inicial)
     * 
     * @param Request $request
     * @return Response
     */
    public function index(Request $request): Response
    {
        try {
            $user = $request->user();

            // Disparar evento de domínio
            Event::dispatch(new HomePageViewed(
                $user?->id ?? 0,
                new \DateTimeImmutable()
            ));

            // Renderizar via Inertia com dados do módulo
            return Inertia::render('Home', [
                'user' => $user,
                'module' => 'home',
                'timestamp' => now()->toISOString(),
            ]);

        } catch (\Exception $e) {
            \Log::error('Erro na página inicial', [
                'error' => $e->getMessage(),
                'user_id' => $user?->id ?? 'guest'
            ]);

            return Inertia::render('Error', [
                'message' => 'Erro ao carregar página inicial',
                'status' => 500
            ]);
        }
    }

    /**
     * Método __invoke para compatibilidade com single-action
     */
    public function __invoke(Request $request): Response
    {
        return $this->index($request);
    }
}
