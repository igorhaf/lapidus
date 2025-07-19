#!/bin/bash

# Script personalizado para executar comandos do Laravel Sail
# Uso: ./sail artisan migrate

if [ "$1" = "artisan" ]; then
    # Se o primeiro argumento for "artisan", execute o comando no container
    shift  # Remove o primeiro argumento (artisan)
    ./vendor/bin/sail exec laravel.test bash -c "cd /var/www/html && php artisan $@"
else
    # Para outros comandos, use o Sail padrão
    ./vendor/bin/sail "$@"
fi 