<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

/**
 * Controller base para arquitetura modular
 * Segue padrões de Clean Architecture e Domain-Driven Design
 */
class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;

    /**
     * Resposta JSON padronizada para APIs
     */
    protected function jsonResponse($data, $status = 200, $message = null)
    {
        return response()->json([
            'data' => $data,
            'meta' => [
                'status' => $status >= 200 && $status < 300 ? 'success' : 'error',
                'message' => $message,
                'timestamp' => now()->toISOString(),
            ]
        ], $status);
    }

    /**
     * Resposta de erro padronizada
     */
    protected function errorResponse($message, $status = 400, $errors = null)
    {
        return response()->json([
            'error' => [
                'message' => $message,
                'errors' => $errors,
                'status' => $status,
                'timestamp' => now()->toISOString(),
            ]
        ], $status);
    }
} 