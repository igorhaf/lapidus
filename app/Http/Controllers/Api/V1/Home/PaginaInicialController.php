<?php

namespace App\Http\Controllers\Api\V1\Home;

use App\Http\Controllers\Api\V1\Controller as ApiController;
use App\Application\Home\UseCases\GetHomePageDataUseCase;
use App\Application\Home\UseCases\SubmitContactFormUseCase;
use App\Application\Home\DTOs\GetHomePageDataInputDTO;
use App\Application\Home\DTOs\SubmitContactFormInputDTO;
use App\Http\Requests\Home\ContactFormRequest;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

/**
 * Controller API para página inicial
 * Agora com suporte a formulário de contato
 */
class PaginaInicialController extends ApiController
{
    public function __construct(
        private readonly GetHomePageDataUseCase $getHomePageDataUseCase,
        private readonly SubmitContactFormUseCase $submitContactFormUseCase
    ) {}

    /**
     * GET - Retorna dados da página inicial
     */
    public function index(Request $request): JsonResponse
    {
        try {
            $inputDTO = GetHomePageDataInputDTO::fromRequest([
                'user_id' => $request->user()?->id,
                'user_ip' => $request->ip(),
                'user_agent' => $request->userAgent(),
            ]);

            $outputDTO = $this->getHomePageDataUseCase->__invoke($inputDTO);

            return $this->apiResponse($outputDTO->toArray());

        } catch (\Exception $e) {
            \Log::error('Erro na API da página inicial', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return $this->apiError('Erro ao carregar dados da página inicial', 500);
        }
    }

    /**
     * POST - Processa formulário de contato
     */
    public function submitContact(ContactFormRequest $request): JsonResponse
    {
        try {
            $inputDTO = SubmitContactFormInputDTO::fromRequest([
                'name' => $request->name,
                'email' => $request->email,
                'phone' => $request->phone,
                'subject' => $request->subject,
                'message' => $request->message,
                'preferred_contact' => $request->preferred_contact,
                'newsletter' => $request->newsletter,
                'user_ip' => $request->ip(),
                'user_agent' => $request->userAgent(),
            ]);

            $outputDTO = $this->submitContactFormUseCase->__invoke($inputDTO);

            return $this->apiResponse($outputDTO->toArray(), 201);

        } catch (\Exception $e) {
            \Log::error('Erro ao processar formulário de contato', [
                'error' => $e->getMessage(),
                'data' => $request->validated(),
                'trace' => $e->getTraceAsString()
            ]);

            return $this->apiError('Erro ao enviar mensagem. Tente novamente.', 500);
        }
    }
} 