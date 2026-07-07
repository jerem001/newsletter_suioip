<?php

declare(strict_types=1);

namespace Modules\Users\Domain\Entities;

use Modules\Users\Domain\ValueObjects\Email;

final class UserEntity
{
    public function __construct(
        private readonly ?int $id,
        private string $name,
        private Email $email,
        private bool $active,
        private array $roles,
        private readonly ?\DateTimeImmutable $createdAt = null,
    ) {
    }

    public function id(): ?int
    {
        return $this->id;
    }

    public function name(): string
    {
        return $this->name;
    }

    public function email(): Email
    {
        return $this->email;
    }

    public function isActive(): bool
    {
        return $this->active;
    }

    public function roles(): array
    {
        return $this->roles;
    }

    public function createdAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function rename(string $name): void
    {
        if (trim($name) === '') {
            throw new \InvalidArgumentException('Le nom ne peut pas être vide.');
        }

        $this->name = $name;
    }

    public function deactivate(): void
    {
        $this->active = false;
    }

    public function activate(): void
    {
        $this->active = true;
    }

    public function changeEmail(Email $email): void
    {
        $this->email = $email;
    }

    public function assignRoles(array $roles): void
    {
        $this->roles = $roles;
    }
}
