<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\V1\Home\HomeController;

Route::prefix('api/v1')
    //->middleware(['auth:sanctum', 'throttle:api'])
    ->middleware(['throttle:api'])
    ->group(function() {
        Route::apiResource('pagina-inicial', HomeController::class);
    });