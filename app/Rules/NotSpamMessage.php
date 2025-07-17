<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

/**
 * Regra para detectar spam em mensagens
 */
class NotSpamMessage implements ValidationRule
{
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $spamKeywords = [
            'buy now', 'click here', 'free money', 'lottery', 'viagra',
            'compre agora', 'clique aqui', 'dinheiro grátis', 'loteria'
        ];

        $lowerValue = strtolower($value);
        
        foreach ($spamKeywords as $keyword) {
            if (str_contains($lowerValue, $keyword)) {
                $fail('A mensagem contém conteúdo suspeito.');
                return;
            }
        }

        // Verificar se tem muitos links
        $linkCount = preg_match_all('/https?:\/\/[^\s]+/', $value);
        if ($linkCount > 3) {
            $fail('A mensagem contém muitos links.');
        }
    }
} 