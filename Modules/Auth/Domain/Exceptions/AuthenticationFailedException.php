<?php

declare(strict_types=1);

namespace Modules\Auth\Domain\Exceptions;

use DomainException;

final class AuthenticationFailedException extends DomainException
{
    public static function invalidCredentials(): self
    {
        return new self('Identifiants invalides.');
    }

    public static function inactiveAccount(): self
    {
        return new self('Ce compte est désactivé.');
    }

    public static function strategyUnavailable(string $strategy): self
    {
        return new self("La stratégie d'authentification « {$strategy} » n'est pas activée sur ce serveur.");
    }
}
