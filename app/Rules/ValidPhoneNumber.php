<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

/**
 * Regra customizada para validação de telefone
 */
class ValidPhoneNumber implements ValidationRule
{
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (empty($value)) {
            return; // Telefone é opcional
        }

        // Remove caracteres especiais
        $cleanValue = preg_replace('/[^0-9+()-]/', '', $value);
        
        // Valida formato brasileiro
        if (!preg_match('/^(\+55\s?)?(\(?\d{2}\)?\s?)?(\d{4,5}-?\d{4})$/', $cleanValue)) {
            $fail('O número de telefone deve estar em formato válido (ex: (11) 99999-9999)');
        }
    }
} 