<?php

declare(strict_types=1);

namespace Modules\Users\Application\UseCases;

use Modules\Users\Application\DTO\CreateUserData;
use Modules\Users\Domain\Entities\UserEntity;
use Modules\Users\Domain\Exceptions\EmailAlreadyTakenException;
use Modules\Users\Domain\Repositories\UserRepositoryInterface;
use Modules\Users\Domain\ValueObjects\Email;

final class CreateUserUseCase
{
    public function __construct(private readonly UserRepositoryInterface $users)
    {
    }

    public function handle(CreateUserData $data): UserEntity
    {
        $email = new Email($data->email);

        if ($this->users->existsByEmail($email->value())) {
            throw EmailAlreadyTakenException::forEmail($email->value());
        }

        $entity = new UserEntity(
            id: null,
            name: $data->name,
            email: $email,
            active: $data->active,
            roles: $data->roles,
        );

        return $this->users->create($entity, $data->password);
    }
}
