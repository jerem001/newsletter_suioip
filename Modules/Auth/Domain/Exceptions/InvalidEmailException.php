<?php

declare(strict_types=1);

namespace Modules\Auth\Domain\Exceptions;

use DomainException;

final class InvalidEmailException extends DomainException
{
    public static function forValue(string $value): self
    {
        return new self("L'adresse email « {$value} » est invalide.");
    }
}
