<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Contact;
use App\Models\HomePageView;
use App\Models\User;

/**
 * Seeder para popular dados do módulo Home
 */
class HomeSeeder extends Seeder
{
    public function run(): void
    {
        $this->command->info('🏠 Populando dados do módulo Home...');

        // Criar contatos de exemplo
        $this->seedContacts();

        // Criar visualizações da página
        $this->seedPageViews();

        $this->command->info('✅ Módulo Home populado com sucesso!');
    }

    private function seedContacts(): void
    {
        $this->command->info('📧 Criando contatos...');

        // Contatos normais
        Contact::factory()->count(15)->create();

        // Contatos urgentes
        Contact::factory()->urgent()->count(3)->create();

        // Contatos já respondidos
        Contact::factory()->responded()->count(5)->create();

        // Contatos que querem newsletter
        Contact::factory()->withNewsletter()->count(8)->create();

        // Contatos apenas por email
        Contact::factory()->emailOnly()->count(4)->create();

        $this->command->info("📧 {$this->getTotalContacts()} contatos criados");
    }

    private function seedPageViews(): void
    {
        $this->command->info('👀 Criando visualizações da página...');

        // Visualizações de visitantes
        HomePageView::factory()->guest()->count(50)->create();

        // Visualizações de usuários autenticados
        HomePageView::factory()->authenticated()->count(20)->create();

        // Visualizações de hoje
        HomePageView::factory()->today()->count(10)->create();

        // Visualizações desta semana
        HomePageView::factory()->thisWeek()->count(15)->create();

        // Visualizações mobile
        HomePageView::factory()->mobile()->count(25)->create();

        $this->command->info("👀 {$this->getTotalPageViews()} visualizações criadas");
    }

    private function getTotalContacts(): int
    {
        return Contact::count();
    }

    private function getTotalPageViews(): int
    {
        return HomePageView::count();
    }
} 