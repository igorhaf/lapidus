<?php

namespace App\Domain\Home\Enums;

/**
 * Enum para tipos de usuário
 */
enum UserType: string
{
    case GUEST = 'guest';
    case REGISTERED = 'registered';
    case ADMIN = 'admin';

    public function getLabel(): string
    {
        return match($this) {
            self::GUEST => 'Visitante',
            self::REGISTERED => 'Usuário Registrado',
            self::ADMIN => 'Administrador',
        };
    }

    public function canAccessAdmin(): bool
    {
        return $this === self::ADMIN;
    }
} 