<?php
use App\Http\Controllers\Api\V1\Home\PaginaInicialController;

Route::prefix('api/v1')
    //->middleware(['auth:sanctum', 'throttle:api'])
    ->middleware(['throttle:api'])
    ->group(function() {
        Route::apiResource('pagina-inicial', PaginaInicialController::class);
    });