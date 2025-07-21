<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Contact;
use App\Domain\Home\Enums\ContactStatus;

/**
 * Factory para gerar dados de teste para contatos
 */
class ContactFactory extends Factory
{
    protected $model = Contact::class;

    public function definition(): array
    {
        $preferredContacts = ['email', 'phone', 'whatsapp'];
        $urgentWords = ['urgente', 'problema', 'crítico', 'emergência'];

        return [
            'contact_id' => fake()->uuid(),
            'name' => fake('pt_BR')->name(),
            'email' => fake()->unique()->safeEmail(),
            'phone' => fake('pt_BR')->cellphone(false),
            'subject' => fake('pt_BR')->sentence(4),
            'message' => fake('pt_BR')->paragraphs(2, true),
            'preferred_contact' => fake()->randomElement($preferredContacts),
            'newsletter' => fake()->boolean(30), // 30% chance de querer newsletter
            'status' => fake()->randomElement(['pending', 'read', 'responded', 'closed']),
            'user_ip' => fake()->ipv4(),
            'user_agent' => fake()->userAgent(),
        ];
    }

    /**
     * Estado para contato urgente
     */
    public function urgent(): static
    {
        return $this->state(fn () => [
            'subject' => 'URGENTE: ' . fake('pt_BR')->sentence(3),
            'message' => fake()->randomElement([
                'Preciso urgentemente de ajuda com meu sistema.',
                'Temos um problema crítico no nosso site.',
                'Emergência! O sistema parou de funcionar.',
                'Situação urgente, preciso falar hoje mesmo.',
            ]) . ' ' . fake('pt_BR')->paragraph(),
        ]);
    }

    /**
     * Estado para contato já respondido
     */
    public function responded(): static
    {
        return $this->state(fn () => [
            'status' => 'responded',
        ]);
    }

    /**
     * Estado para contato pendente
     */
    public function pending(): static
    {
        return $this->state(fn () => [
            'status' => 'pending',
        ]);
    }

    /**
     * Estado para contato que quer newsletter
     */
    public function withNewsletter(): static
    {
        return $this->state(fn () => [
            'newsletter' => true,
        ]);
    }

    /**
     * Estado para contato sem telefone
     */
    public function emailOnly(): static
    {
        return $this->state(fn () => [
            'phone' => null,
            'preferred_contact' => 'email',
        ]);
    }
} 