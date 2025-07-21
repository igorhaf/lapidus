<?php
Route::prefix('api/v1')
    ->middleware(['auth:sanctum', 'throttle:api'])
    ->group(function() {
        Route::apiResource('pagina-inicial', PaginaInicialController::class);
    });