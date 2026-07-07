<?php

declare(strict_types=1);

namespace Modules\Auth\Infrastructure\Persistence\Eloquent;

use Illuminate\Support\Facades\Hash;
use Modules\Auth\Domain\Entities\AuthenticatedUser;
use Modules\Auth\Domain\Repositories\UserAuthRepositoryInterface;
use Modules\Auth\Domain\ValueObjects\AuthStrategy;
use Modules\Auth\Domain\ValueObjects\Email;
use Modules\Users\Infrastructure\Persistence\Models\UserModel;

final class EloquentUserAuthRepository implements UserAuthRepositoryInterface
{
    public function findByEmail(Email $email): ?AuthenticatedUser
    {
        $model = UserModel::query()->where('email', $email->value())->first();

        return $model ? $this->toDomain($model) : null;
    }

    public function findById(int $id): ?AuthenticatedUser
    {
        $model = UserModel::query()->find($id);

        return $model ? $this->toDomain($model) : null;
    }

    public function verifyPassword(AuthenticatedUser $user, string $plainPassword): bool
    {
        $model = UserModel::query()->find($user->id());

        if ($model === null || $model->password === null) {
            return false;
        }

        return Hash::check($plainPassword, $model->password);
    }

    public function upsertFromExternalAttributes(Email $email, string $name, array $roles = []): AuthenticatedUser
    {
        $model = UserModel::query()->updateOrCreate(
            ['email' => $email->value()],
            [
                'name' => $name,
                'password' => null, // aucun mot de passe local pour les comptes SSO/LDAP
                'is_active' => true,
            ]
        );

        if ($roles !== []) {
            $model->syncRoles($roles);
        }

        return $this->toDomain($model->refresh());
    }

    private function toDomain(UserModel $model): AuthenticatedUser
    {
        return new AuthenticatedUser(
            id: $model->id,
            name: $model->name,
            email: new Email($model->email),
            strategy: AuthStrategy::from($model->auth_strategy ?? 'local'),
            roles: $model->getRoleNames()->toArray(),
            active: (bool) $model->is_active,
        );
    }
}
