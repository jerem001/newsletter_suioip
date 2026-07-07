<?php

declare(strict_types=1);

namespace Modules\Users\Application\DTO;

final class CreateUserData
{
    public function __construct(
        public readonly string $name,
        public readonly string $email,
        public readonly ?string $password,
        public readonly array $roles = [],
        public readonly bool $active = true,
    ) {
    }
}
