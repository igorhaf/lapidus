<?php
use Illuminate\Support\Facades\Route;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Support\Facades\RateLimiter;
use App\Http\Controllers\Api\V1\Home\HomeController;

// Definição do rate limiter 'api'
RateLimiter::for('api', function ($request) {
    return Limit::perMinute(60)->by(optional($request->user())->id ?: $request->ip());
});

Route::prefix('v1')
    ->middleware(['throttle:api'])
    ->group(function() {
        Route::apiResource('pagina-inicial', HomeController::class);
    });