<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\V1\Home\PaginaInicialController;

Route::prefix('api/v1')
    ->middleware(['throttle:api'])
    ->group(function() {
        Route::apiResource('pagina-inicial', PaginaInicialController::class);
    });