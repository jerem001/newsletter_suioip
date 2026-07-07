<?php

declare(strict_types=1);

namespace Modules\Users\Application\UseCases;

use Modules\Users\Domain\Exceptions\UserNotFoundException;
use Modules\Users\Domain\Repositories\UserRepositoryInterface;

final class DeleteUserUseCase
{
    public function __construct(private readonly UserRepositoryInterface $users)
    {
    }

    public function handle(int $id): void
    {
        if ($this->users->findById($id) === null) {
            throw UserNotFoundException::withId($id);
        }

        $this->users->delete($id);
    }
}
