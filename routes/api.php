<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Support\Facades\RateLimiter;
use App\Http\Controllers\Api\V1\Home\PaginaInicialController;

// Definição do rate limiter 'api'
RateLimiter::for('api', function ($request) {
    return Limit::perMinute(60)->by(optional($request->user())->id ?: $request->ip());
});

Route::prefix('api/v1')
    ->middleware(['throttle:api'])
    ->group(function() {
        // GET - Dados da página inicial
        Route::get('pagina-inicial', [PaginaInicialController::class, 'index']);
        
        // POST - Enviar formulário de contato
        Route::post('pagina-inicial/contact', [PaginaInicialController::class, 'submitContact']);
    });
