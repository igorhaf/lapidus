<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

/**
 * Regra para validar preferência de contato
 */
class ValidPreferredContact implements ValidationRule
{
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (empty($value)) {
            return; // É opcional
        }

        $validOptions = ['email', 'phone', 'whatsapp'];
        
        if (!in_array($value, $validOptions)) {
            $fail('A preferência de contato deve ser: email, phone ou whatsapp.');
        }
    }
} 