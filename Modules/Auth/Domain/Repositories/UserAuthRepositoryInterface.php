<?php

declare(strict_types=1);

namespace Modules\Auth\Domain\Repositories;

use Modules\Auth\Domain\Entities\AuthenticatedUser;
use Modules\Auth\Domain\ValueObjects\Email;

/**
 * Port du domaine : contrat que doit respecter toute implémentation
 * d'infrastructure (Eloquent, LDAP, ...) pour fournir des utilisateurs
 * au cœur métier, sans que celui-ci ne connaisse le détail technique.
 */
interface UserAuthRepositoryInterface
{
    public function findByEmail(Email $email): ?AuthenticatedUser;

    public function findById(int $id): ?AuthenticatedUser;

    public function verifyPassword(AuthenticatedUser $user, string $plainPassword): bool;

    /**
     * Crée (jit-provisioning) ou met à jour un utilisateur local à partir
     * d'attributs distants (CAS/LDAP).
     */
    public function upsertFromExternalAttributes(Email $email, string $name, array $roles = []): AuthenticatedUser;
}
