<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

/**
 * Seeder principal do sistema
 * Seguindo a arquitetura de domínio do projeto
 */
class DatabaseSeeder extends Seeder
{
    /**
     * Executar os seeders na ordem correta
     */
    public function run(): void
    {
        // Seeders de usuários e autenticação
        $this->call([
            UserSeeder::class,
        ]);

        // Seeders de módulos de domínio (quando implementados)
        // $this->call([
        //     HomeSeeder::class,
        // ]);
    }
}
