<?php

declare(strict_types=1);

namespace Modules\Users\Domain\Exceptions;

use DomainException;

final class UserNotFoundException extends DomainException
{
    public static function withId(int $id): self
    {
        return new self("Utilisateur #{$id} introuvable.");
    }
}
