<?php

namespace App\Http\Controllers\Api\V1\Home;

use App\Http\Controllers\Api\V1\Controller as ApiController;
use App\Application\Home\UseCases\GetHomePageDataUseCase;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

/**
 * Controller API para página inicial
 * Retorna dados JSON para consumo via API
 */
class PaginaInicialController extends ApiController
{
    public function __construct(
        private GetHomePageDataUseCase $getHomePageDataUseCase
    ) {}

    /**
     * Retorna dados da página inicial via API
     */
    public function index(Request $request): JsonResponse
    {
        try {
            $user = $request->user();
            
            // Usar UseCase para obter dados
            $data = $this->getHomePageDataUseCase->execute($user?->id);
            
            // Adicionar dados do usuário
            $data['user'] = $user ? [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
            ] : null;

            return $this->apiResponse($data);

        } catch (\Exception $e) {
            \Log::error('Erro na API da página inicial', [
                'error' => $e->getMessage(),
                'user_id' => $user?->id ?? 'guest'
            ]);

            return $this->apiError('Erro ao carregar dados da página inicial', 500);
        }
    }
} 