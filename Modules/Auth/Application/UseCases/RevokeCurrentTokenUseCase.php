<?php

declare(strict_types=1);

namespace Modules\Auth\Application\UseCases;

use Modules\Auth\Application\Ports\TokenIssuer;

final class RevokeCurrentTokenUseCase
{
    public function __construct(private readonly TokenIssuer $tokenIssuer)
    {
    }

    public function handle(int $userId, string $tokenId): void
    {
        $this->tokenIssuer->revoke($userId, $tokenId);
    }
}
