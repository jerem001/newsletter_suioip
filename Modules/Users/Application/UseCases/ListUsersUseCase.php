<?php

declare(strict_types=1);

namespace Modules\Users\Application\UseCases;

use Modules\Users\Domain\Repositories\UserRepositoryInterface;
use Modules\Users\Domain\ValueObjects\Paginated;

final class ListUsersUseCase
{
    public function __construct(private readonly UserRepositoryInterface $users)
    {
    }

    public function handle(int $perPage = 15, ?string $search = null): Paginated
    {
        return $this->users->paginate($perPage, $search);
    }
}
