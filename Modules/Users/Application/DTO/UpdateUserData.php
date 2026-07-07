<?php

declare(strict_types=1);

namespace Modules\Users\Application\DTO;

final class UpdateUserData
{
    public function __construct(
        public readonly int $id,
        public readonly ?string $name = null,
        public readonly ?string $email = null,
        public readonly ?array $roles = null,
        public readonly ?bool $active = null,
    ) {
    }
}
