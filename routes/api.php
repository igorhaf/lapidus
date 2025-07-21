<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Support\Facades\RateLimiter;
use App\Http\Controllers\Api\V1\Home\PaginaInicialController;

// Definição do rate limiter 'api'
RateLimiter::for('api', function ($request) {
    return Limit::perMinute(60)->by(optional($request->user())->id ?: $request->ip());
});

Route::prefix('v1')
    ->group(function() {
        // Teste simples
        Route::get('test', function() {
            return response()->json(['status' => 'API funcionando']);
        });
        
        // GET - Dados da página inicial
        Route::get('pagina-inicial', [PaginaInicialController::class, 'index']);
        
        // POST - Enviar formulário de contato
        Route::post('pagina-inicial/contact', [PaginaInicialController::class, 'submitContact']);
    });

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


