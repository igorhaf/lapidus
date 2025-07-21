<?php

namespace App\Domain\Home\Enums;

/**
 * Enum para tipos de visualização de página
 */
enum PageViewType: string
{
    case HOME = 'home';
    case ABOUT = 'about';
    case CONTACT = 'contact';
    case SERVICES = 'services';

    public function getLabel(): string
    {
        return match($this) {
            self::HOME => 'Página Inicial',
            self::ABOUT => 'Sobre Nós',
            self::CONTACT => 'Contato',
            self::SERVICES => 'Serviços',
        };
    }

    public function isPublic(): bool
    {
        return in_array($this, [self::HOME, self::ABOUT, self::SERVICES]);
    }
} 