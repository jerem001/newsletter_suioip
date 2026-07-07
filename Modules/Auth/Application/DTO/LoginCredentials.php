<?php

declare(strict_types=1);

namespace Modules\Auth\Application\DTO;

final class LoginCredentials
{
    public function __construct(
        public readonly string $email,
        public readonly string $password,
        public readonly ?string $deviceName = null,
    ) {
    }
}
