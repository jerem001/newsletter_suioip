<?php

declare(strict_types=1);

namespace Modules\Auth\Domain\ValueObjects;

use Modules\Auth\Domain\Exceptions\InvalidEmailException;

final class Email
{
    private string $value;

    public function __construct(string $value)
    {
        $value = trim(strtolower($value));

        if (! filter_var($value, FILTER_VALIDATE_EMAIL)) {
            throw InvalidEmailException::forValue($value);
        }

        $this->value = $value;
    }

    public function value(): string
    {
        return $this->value;
    }

    public function equals(Email $other): bool
    {
        return $this->value === $other->value;
    }

    public function __toString(): string
    {
        return $this->value;
    }
}
