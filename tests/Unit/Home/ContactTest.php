<?php

namespace Tests\Unit\Home;

use PHPUnit\Framework\TestCase;
use App\Domain\Home\Entities\Contact;
use App\Domain\Home\ValueObjects\ContactId;
use App\Domain\Home\ValueObjects\Email;
use App\Domain\Home\ValueObjects\Phone;
use App\Domain\Home\Enums\ContactStatus;

/**
 * Teste unitário da entidade Contact
 */
class ContactTest extends TestCase
{
    /** @test */
    public function pode_criar_contato_completo()
    {
        $contact = new Contact(
            id: new ContactId('123e4567-e89b-12d3-a456-426614174000'),
            name: 'João Silva',
            email: new Email('joao@example.com'),
            phone: new Phone('11999999999'),
            subject: 'Teste',
            message: 'Mensagem de teste',
            preferredContact: 'email',
            newsletter: true,
            status: ContactStatus::PENDING,
            userIp: '192.168.1.1',
            userAgent: 'Mozilla/5.0...'
        );

        $this->assertEquals('123e4567-e89b-12d3-a456-426614174000', $contact->getId()->getValue());
        $this->assertEquals('João Silva', $contact->getName());
        $this->assertEquals('joao@example.com', $contact->getEmail()->getValue());
        $this->assertEquals('11999999999', $contact->getPhone()->getValue());
        $this->assertEquals('Teste', $contact->getSubject());
        $this->assertEquals('Mensagem de teste', $contact->getMessage());
        $this->assertEquals('email', $contact->getPreferredContact());
        $this->assertTrue($contact->wantsNewsletter());
        $this->assertEquals(ContactStatus::PENDING, $contact->getStatus());
        $this->assertEquals('192.168.1.1', $contact->getUserIp());
        $this->assertEquals('Mozilla/5.0...', $contact->getUserAgent());
    }

    /** @test */
    public function pode_criar_contato_sem_telefone()
    {
        $contact = new Contact(
            id: new ContactId('123e4567-e89b-12d3-a456-426614174000'),
            name: 'João Silva',
            email: new Email('joao@example.com'),
            phone: null,
            subject: 'Teste',
            message: 'Mensagem de teste',
            preferredContact: 'email',
            newsletter: false
        );

        $this->assertNull($contact->getPhone());
        $this->assertFalse($contact->wantsNewsletter());
    }

    /** @test */
    public function identifica_contato_urgente_por_palavras_chave()
    {
        $urgentMessages = [
            'Preciso urgentemente de ajuda',
            'Temos um problema crítico',
            'Esta é uma emergência',
            'Situação crítica no sistema',
        ];

        foreach ($urgentMessages as $message) {
            $contact = new Contact(
                id: new ContactId('123e4567-e89b-12d3-a456-426614174000'),
                name: 'João Silva',
                email: new Email('joao@example.com'),
                phone: null,
                subject: 'Teste',
                message: $message,
                preferredContact: 'email',
                newsletter: false
            );

            $this->assertTrue($contact->isUrgent(), "Mensagem '{$message}' deveria ser identificada como urgente");
        }
    }

    /** @test */
    public function nao_identifica_contato_normal_como_urgente()
    {
        $normalMessages = [
            'Gostaria de saber mais sobre os serviços',
            'Tenho interesse em contratar',
            'Podemos marcar uma reunião?',
            'Obrigado pelo excelente trabalho',
        ];

        foreach ($normalMessages as $message) {
            $contact = new Contact(
                id: new ContactId('123e4567-e89b-12d3-a456-426614174000'),
                name: 'João Silva',
                email: new Email('joao@example.com'),
                phone: null,
                subject: 'Teste',
                message: $message,
                preferredContact: 'email',
                newsletter: false
            );

            $this->assertFalse($contact->isUrgent(), "Mensagem '{$message}' não deveria ser identificada como urgente");
        }
    }

    /** @test */
    public function usa_status_padrao_se_nao_informado()
    {
        $contact = new Contact(
            id: new ContactId('123e4567-e89b-12d3-a456-426614174000'),
            name: 'João Silva',
            email: new Email('joao@example.com'),
            phone: null,
            subject: 'Teste',
            message: 'Mensagem de teste',
            preferredContact: 'email',
            newsletter: false
        );

        $this->assertEquals(ContactStatus::PENDING, $contact->getStatus());
    }
} 