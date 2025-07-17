<?php

namespace App\ViewComposers;

use Illuminate\View\View;

/**
 * ViewComposer para formulário de contato
 */
class ContactFormComposer
{
    public function compose(View $view): void
    {
        $view->with([
            'contactSubjects' => [
                'general' => 'Assunto Geral',
                'support' => 'Suporte Técnico',
                'sales' => 'Vendas',
                'partnership' => 'Parceria',
                'bug_report' => 'Reportar Bug',
                'feature_request' => 'Solicitar Funcionalidade',
                'other' => 'Outro',
            ],
            'contactMethods' => [
                'email' => 'E-mail',
                'phone' => 'Telefone',
                'whatsapp' => 'WhatsApp',
            ],
            'formDefaults' => [
                'preferred_contact' => 'email',
                'newsletter' => false,
            ],
        ]);
    }
} 