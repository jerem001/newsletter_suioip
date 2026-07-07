<?php

declare(strict_types=1);

namespace Modules\Users\Domain\Exceptions;

use DomainException;

final class EmailAlreadyTakenException extends DomainException
{
    public static function forEmail(string $email): self
    {
        return new self("L'adresse « {$email} » est déjà utilisée par un autre compte.");
    }
}
