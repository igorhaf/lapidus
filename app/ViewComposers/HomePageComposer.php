<?php

namespace App\ViewComposers;

use Illuminate\View\View;
use App\Domain\Home\Interfaces\Repositories\HomeRepositoryInterface;
use App\Domain\Home\Interfaces\Repositories\ContactRepositoryInterface;

/**
 * ViewComposer para página inicial
 */
class HomePageComposer
{
    public function __construct(
        private readonly HomeRepositoryInterface $homeRepository,
        private readonly ContactRepositoryInterface $contactRepository
    ) {}

    public function compose(View $view): void
    {
        // Dados para a página inicial
        $view->with([
            'pageStats' => $this->homeRepository->getPageStats(),
            'recentContacts' => $this->contactRepository->list([], 5),
            'urgentContacts' => $this->contactRepository->findUrgent(),
            'contactForm' => [
                'subjects' => [
                    'general' => 'Assunto Geral',
                    'support' => 'Suporte Técnico',
                    'sales' => 'Vendas',
                    'partnership' => 'Parceria',
                    'other' => 'Outro',
                ],
                'preferredContacts' => [
                    'email' => 'E-mail',
                    'phone' => 'Telefone',
                    'whatsapp' => 'WhatsApp',
                ],
            ],
        ]);
    }
}
