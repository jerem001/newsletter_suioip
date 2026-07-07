<?php

declare(strict_types=1);

namespace Modules\Users\Domain\Repositories;

use Modules\Users\Domain\Entities\UserEntity;
use Modules\Users\Domain\ValueObjects\Paginated;

interface UserRepositoryInterface
{
    public function paginate(int $perPage = 15, ?string $search = null): Paginated;

    public function findById(int $id): ?UserEntity;

    public function existsByEmail(string $email, ?int $excludingId = null): bool;

    public function create(UserEntity $user, ?string $plainPassword): UserEntity;

    public function update(UserEntity $user): UserEntity;

    public function delete(int $id): void;
}
