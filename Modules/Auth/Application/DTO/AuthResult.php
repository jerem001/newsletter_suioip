<?php

declare(strict_types=1);

namespace Modules\Auth\Application\DTO;

use Modules\Auth\Domain\Entities\AuthenticatedUser;

final class AuthResult
{
    public function __construct(
        public readonly AuthenticatedUser $user,
        public readonly string $token,
        public readonly string $tokenType = 'Bearer',
    ) {
    }
}
