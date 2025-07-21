<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

/**
 * Seeder para criação de usuários do sistema
 * Seguindo a arquitetura de domínio do projeto
 */
class UserSeeder extends Seeder
{
    /**
     * Executar o seeder
     */
    public function run(): void
    {
        // Criar usuário administrador
        DB::table('users')->insertOrIgnore([
            'name' => 'Administrador',
            'email' => 'admin@example.com',
            'email_verified_at' => now(),
            'password' => Hash::make('admin'),
            'remember_token' => null,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $this->command->info('Usuário administrador criado: admin@example.com / admin');
    }
} 