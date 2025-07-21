<?php

namespace App\Http\Controllers;

use App\Application\Home\UseCases\GetHomePageDataUseCase;
use App\Application\Home\DTOs\GetHomePageDataInputDTO;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

/**
 * Controller Web para página inicial
 */
class HomeController extends Controller
{
    // Simplificando para teste inicial

    /**
     * Página inicial - renderiza através do Inertia com dados do módulo Home
     */
    public function index(Request $request): Response
    {
        // Log para debug
        \Log::info('HomeController@index executado!', [
            'user' => $request->user()?->name ?? 'visitante',
            'ip' => $request->ip()
        ]);
        
        try {
            // Usando view padrão com dados customizados
            $data = [
                'canLogin' => \Route::has('login'),
                'canRegister' => \Route::has('register'),
                'customMessage' => 'Página inicial do módulo Home funcionando! Controller executado em ' . now()->format('H:i:s'),
                'homeStats' => [
                    'total_views' => 100,
                    'unique_visitors' => 85,
                    'recent_views' => 5,
                    'bounce_rate' => '42%',
                ],
                'user' => $request->user(),
                'laravelVersion' => app()->version(),
                'phpVersion' => PHP_VERSION,
            ];
            
            \Log::info('Dados sendo enviados para Welcome view:', $data);
            
            return Inertia::render('Welcome', $data);

        } catch (\Exception $e) {
            \Log::error('Erro na página inicial', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            // Fallback para página padrão se houver erro
            return Inertia::render('Welcome', [
                'canLogin' => \Route::has('login'),
                'canRegister' => \Route::has('register'),
                'error' => 'Erro ao carregar dados da página inicial',
            ]);
        }
    }
} 