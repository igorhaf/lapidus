<?php

namespace Tests\Feature\Home;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\Contact;
use App\Models\HomePageView;

/**
 * Teste da funcionalidade da página inicial
 */
class HomePageTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    /** @test */
    public function pode_acessar_pagina_inicial()
    {
        $response = $this->get('/');

        $response->assertStatus(200);
        $response->assertInertia(fn ($page) => 
            $page->component('modules/home/ui/index')
                 ->has('canLogin')
                 ->has('canRegister')
        );
    }

    /** @test */
    public function pode_obter_dados_da_api_da_pagina_inicial()
    {
        $response = $this->getJson('/api/v1/pagina-inicial');

        $response->assertStatus(200)
                ->assertJsonStructure([
                    'success',
                    'data' => [
                        'message',
                        'module',
                        'timestamp',
                        'metadata'
                    ]
                ]);
    }

    /** @test */
    public function pode_enviar_formulario_de_contato()
    {
        $contactData = [
            'name' => 'João Silva',
            'email' => 'joao@example.com',
            'phone' => '11999999999',
            'subject' => 'Teste de contato',
            'message' => 'Esta é uma mensagem de teste',
            'preferred_contact' => 'email',
            'newsletter' => true,
        ];

        $response = $this->postJson('/api/v1/pagina-inicial/contact', $contactData);

        $response->assertStatus(201)
                ->assertJsonStructure([
                    'success',
                    'data' => [
                        'message',
                        'contact_id',
                    ]
                ]);

        // Verificar se foi salvo no banco
        $this->assertDatabaseHas('home_contacts', [
            'name' => 'João Silva',
            'email' => 'joao@example.com',
            'subject' => 'Teste de contato',
        ]);
    }

    /** @test */
    public function formulario_de_contato_valida_campos_obrigatorios()
    {
        $response = $this->postJson('/api/v1/pagina-inicial/contact', []);

        $response->assertStatus(422)
                ->assertJsonValidationErrors(['name', 'email', 'subject', 'message']);
    }

    /** @test */
    public function formulario_de_contato_valida_email()
    {
        $contactData = [
            'name' => 'João Silva',
            'email' => 'email-inválido',
            'subject' => 'Teste',
            'message' => 'Mensagem de teste',
        ];

        $response = $this->postJson('/api/v1/pagina-inicial/contact', $contactData);

        $response->assertStatus(422)
                ->assertJsonValidationErrors(['email']);
    }

    /** @test */
    public function registra_visualizacao_da_pagina()
    {
        // Contar visualizações antes
        $viewsBefore = HomePageView::count();

        // Acessar a API da página inicial
        $this->getJson('/api/v1/pagina-inicial');

        // Verificar se uma nova visualização foi registrada
        $this->assertEquals($viewsBefore + 1, HomePageView::count());
    }

    /** @test */
    public function usuario_autenticado_pode_acessar_pagina()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get('/');

        $response->assertStatus(200);
    }

    /** @test */
    public function contato_urgente_eh_identificado_corretamente()
    {
        $urgentContact = Contact::factory()->urgent()->create();

        $this->assertTrue($urgentContact->is_urgent);
    }

    /** @test */
    public function contato_normal_nao_eh_urgente()
    {
        $normalContact = Contact::factory()->create();

        $this->assertFalse($normalContact->is_urgent);
    }

    /** @test */
    public function pode_filtrar_contatos_urgentes()
    {
        // Criar contatos normais e urgentes
        Contact::factory()->count(5)->create();
        Contact::factory()->urgent()->count(2)->create();

        $urgentContacts = Contact::urgent()->get();

        $this->assertCount(2, $urgentContacts);
        $urgentContacts->each(function ($contact) {
            $this->assertTrue($contact->is_urgent);
        });
    }
} 