<?php

declare(strict_types=1);

namespace Modules\Auth\Infrastructure\Security;

use Modules\Auth\Application\Ports\TokenIssuer;
use Modules\Auth\Domain\Entities\AuthenticatedUser;
use Modules\Users\Infrastructure\Persistence\Models\UserModel;

final class SanctumTokenIssuer implements TokenIssuer
{
    public function issueFor(AuthenticatedUser $user, string $deviceName): string
    {
        /** @var UserModel $model */
        $model = UserModel::query()->findOrFail($user->id());

        $abilities = $user->roles() === [] ? ['*'] : $user->roles();

        return $model->createToken($deviceName, $abilities)->plainTextToken;
    }

    public function revoke(int $userId, string $tokenId): void
    {
        /** @var UserModel $model */
        $model = UserModel::query()->findOrFail($userId);

        $model->tokens()->where('id', $tokenId)->delete();
    }
}
