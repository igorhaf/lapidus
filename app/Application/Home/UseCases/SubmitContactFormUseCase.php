<?php

namespace App\Application\Home\UseCases;

use App\Application\Home\DTOs\SubmitContactFormInputDTO;
use App\Application\Home\DTOs\SubmitContactFormOutputDTO;
use App\Domain\Home\Interfaces\Repositories\ContactRepositoryInterface;
use App\Domain\Home\Entities\Contact;
use App\Domain\Home\ValueObjects\ContactId;
use App\Domain\Home\ValueObjects\Email;
use App\Domain\Home\ValueObjects\Phone;
use App\Domain\Home\Enums\ContactStatus;
use App\Domain\Home\Events\ContactSubmitted;
use Illuminate\Support\Facades\Event;

/**
 * UseCase para envio do formulário de contato
 */
class SubmitContactFormUseCase
{
    public function __construct(
        private readonly ContactRepositoryInterface $contactRepository
    ) {}

    public function __invoke(SubmitContactFormInputDTO $input): SubmitContactFormOutputDTO
    {
        // Criar entidade de contato
        $contact = new Contact(
            id: ContactId::generate(),
            name: $input->name,
            email: new Email($input->email),
            phone: $input->phone ? new Phone($input->phone) : null,
            subject: $input->subject,
            message: $input->message,
            preferredContact: $input->preferredContact,
            newsletter: $input->newsletter,
            status: ContactStatus::PENDING,
            userIp: $input->userIp,
            userAgent: $input->userAgent
        );

        // Salvar no repositório
        $this->contactRepository->save($contact);

        // Disparar evento de domínio
        Event::dispatch(new ContactSubmitted(
            $contact->getId()->getValue(),
            $contact->getEmail()->getValue(),
            $contact->isUrgent()
        ));

        // Retornar DTO de saída
        return new SubmitContactFormOutputDTO(
            success: true,
            message: 'Mensagem enviada com sucesso! Entraremos em contato em breve.',
            contactId: $contact->getId()->getValue(),
            timestamp: now()->toISOString(),
            metadata: [
                'is_urgent' => $contact->isUrgent(),
                'preferred_contact' => $contact->getPreferredContact(),
                'newsletter' => $contact->wantsNewsletter(),
            ]
        );
    }
} 