<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

// Página inicial - renderizar através do módulo Home
Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

// Rota de teste temporária para debug do login
Route::post('/test-login-real', function (\Illuminate\Http\Request $request) {
    // Simular exatamente o que o LoginRequest faz
    $email = $request->input('email');
    
    \Log::error('DEBUG: Test login real', [
        'email_input' => $email,
        'email_type' => gettype($email),
        'email_is_array' => is_array($email),
        'all_data' => $request->all(),
    ]);
    
    // Simular o throttleKey
    if (is_array($email)) {
        $email = implode('', $email);
    }
    $email = strtolower((string) $email);
    $throttleKey = $email . '|' . $request->ip();
    
    \Log::error('DEBUG: Test throttle key result', [
        'throttle_key' => $throttleKey,
        'throttle_key_type' => gettype($throttleKey),
    ]);
    
    return response()->json(['message' => 'Test completed', 'throttle_key' => $throttleKey]);
})->withoutMiddleware(\Illuminate\Foundation\Http\Middleware\VerifyCsrfToken::class);

Route::get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
