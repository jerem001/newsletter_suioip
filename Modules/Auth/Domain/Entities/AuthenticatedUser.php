<?php

declare(strict_types=1);

namespace Modules\Auth\Domain\Entities;

use Modules\Auth\Domain\ValueObjects\AuthStrategy;
use Modules\Auth\Domain\ValueObjects\Email;

/**
 * Entité de domaine représentant un utilisateur authentifié.
 *
 * Volontairement dépourvue de toute dépendance à Eloquent/Laravel :
 * c'est un objet du cœur métier, mappé depuis/vers l'infrastructure
 * par les repositories (voir Infrastructure/Persistence).
 */
final class AuthenticatedUser
{
    public function __construct(
        private readonly ?int $id,
        private readonly string $name,
        private readonly Email $email,
        private readonly AuthStrategy $strategy,
        private readonly array $roles = [],
        private readonly bool $active = true,
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

    public function strategy(): AuthStrategy
    {
        return $this->strategy;
    }

    public function roles(): array
    {
        return $this->roles;
    }

    public function isActive(): bool
    {
        return $this->active;
    }

    public function hasRole(string $role): bool
    {
        return in_array($role, $this->roles, true);
    }
}
