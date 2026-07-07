<?php

declare(strict_types=1);

namespace Modules\Auth\Application\Ports;

use Modules\Auth\Domain\Entities\AuthenticatedUser;

/**
 * Port de sortie : émettre/révoquer un jeton d'accès API.
 * Implémentation concrète (Sanctum) dans Infrastructure/Security.
 */
interface TokenIssuer
{
    public function issueFor(AuthenticatedUser $user, string $deviceName): string;

    public function revoke(int $userId, string $tokenId): void;
}
