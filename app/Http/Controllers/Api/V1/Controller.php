<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller as BaseController;

/**
 * Controller base para API V1
 * Extende funcionalidades específicas para APIs REST
 */
class Controller extends BaseController
{
    /**
     * Resposta JSON para APIs REST
     */
    protected function apiResponse($data, $status = 200, $message = 'Success')
    {
        return $this->jsonResponse($data, $status, $message);
    }

    /**
     * Resposta de erro para APIs REST
     */
    protected function apiError($message, $status = 400, $errors = null)
    {
        return $this->errorResponse($message, $status, $errors);
    }
} 