<?php

declare(strict_types=1);

namespace Modules\Users\Application\UseCases;

use Modules\Users\Domain\Entities\UserEntity;
use Modules\Users\Domain\Exceptions\UserNotFoundException;
use Modules\Users\Domain\Repositories\UserRepositoryInterface;

final class GetUserUseCase
{
    public function __construct(private readonly UserRepositoryInterface $users)
    {
    }

    public function handle(int $id): UserEntity
    {
        return $this->users->findById($id) ?? throw UserNotFoundException::withId($id);
    }
}
