<?php

declare(strict_types=1);

namespace Modules\Users\Infrastructure\Persistence\Eloquent;

use Illuminate\Support\Facades\Hash;
use Modules\Users\Domain\Entities\UserEntity;
use Modules\Users\Domain\Repositories\UserRepositoryInterface;
use Modules\Users\Domain\ValueObjects\Email;
use Modules\Users\Domain\ValueObjects\Paginated;
use Modules\Users\Infrastructure\Persistence\Models\UserModel;

final class EloquentUserRepository implements UserRepositoryInterface
{
    public function paginate(int $perPage = 15, ?string $search = null): Paginated
    {
        $query = UserModel::query()->orderBy('name');

        if ($search !== null && $search !== '') {
            $needle = mb_strtolower($search);
            $query->where(function ($q) use ($needle): void {
                $q->whereRaw('LOWER(name) LIKE ?', ["%{$needle}%"])
                    ->orWhereRaw('LOWER(email) LIKE ?', ["%{$needle}%"]);
            });
        }

        $paginator = $query->paginate($perPage);

        return new Paginated(
            items: array_map($this->toDomain(...), $paginator->items()),
            total: $paginator->total(),
            perPage: $paginator->perPage(),
            currentPage: $paginator->currentPage(),
        );
    }

    public function findById(int $id): ?UserEntity
    {
        $model = UserModel::query()->find($id);

        return $model ? $this->toDomain($model) : null;
    }

    public function existsByEmail(string $email, ?int $excludingId = null): bool
    {
        return UserModel::query()
            ->where('email', $email)
            ->when($excludingId !== null, fn ($q) => $q->where('id', '!=', $excludingId))
            ->exists();
    }

    public function create(UserEntity $user, ?string $plainPassword): UserEntity
    {
        $model = UserModel::query()->create([
            'name' => $user->name(),
            'email' => $user->email()->value(),
            'password' => $plainPassword !== null ? Hash::make($plainPassword) : null,
            'is_active' => $user->isActive(),
        ]);

        if ($user->roles() !== []) {
            $model->syncRoles($user->roles());
        }

        return $this->toDomain($model->refresh());
    }

    public function update(UserEntity $user): UserEntity
    {
        $model = UserModel::query()->findOrFail($user->id());

        $model->update([
            'name' => $user->name(),
            'email' => $user->email()->value(),
            'is_active' => $user->isActive(),
        ]);

        if ($user->roles() !== []) {
            $model->syncRoles($user->roles());
        }

        return $this->toDomain($model->refresh());
    }

    public function delete(int $id): void
    {
        UserModel::query()->findOrFail($id)->delete();
    }

    private function toDomain(UserModel $model): UserEntity
    {
        return new UserEntity(
            id: $model->id,
            name: $model->name,
            email: new Email($model->email),
            active: (bool) $model->is_active,
            roles: $model->getRoleNames()->toArray(),
            createdAt: $model->created_at?->toDateTimeImmutable(),
        );
    }
}
