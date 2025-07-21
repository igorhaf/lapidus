<?php

namespace App\Domain\Home\Entities;

use App\Domain\Home\ValueObjects\PageViewId;
use App\Domain\Home\Enums\PageViewType;

/**
 * Entidade que representa a página inicial
 */
class HomePage
{
    private array $views = [];

    public function __construct(
        private readonly PageViewType $type = PageViewType::HOME,
        private readonly string $title = 'Página Inicial',
        private readonly string $description = 'Bem-vindo ao nosso site'
    ) {}

    public function getType(): PageViewType
    {
        return $this->type;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function addView(HomePageView $view): void
    {
        $this->views[] = $view;
    }

    public function getViews(): array
    {
        return $this->views;
    }

    public function getViewCount(): int
    {
        return count($this->views);
    }

    public function isPublic(): bool
    {
        return $this->type->isPublic();
    }
} 