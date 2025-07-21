<?php

namespace App\Domain\Home\Interfaces\Repositories;

use App\Domain\Home\Entities\Contact;
use App\Domain\Home\ValueObjects\ContactId;

/**
 * Contrato para repositório de contatos
 */
interface ContactRepositoryInterface
{
    /**
     * Salva um contato
     */
    public function save(Contact $contact): void;

    /**
     * Busca contato por ID
     */
    public function findById(ContactId $id): ?Contact;

    /**
     * Lista contatos com filtros
     */
    public function list(array $filters = [], int $limit = 20): array;

    /**
     * Busca contatos urgentes
     */
    public function findUrgent(): array;

    /**
     * Atualiza status do contato
     */
    public function updateStatus(ContactId $id, string $status): void;
} 