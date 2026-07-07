<?php

declare(strict_types=1);

namespace Modules\Users\Application\UseCases;

use Modules\Users\Application\DTO\UpdateUserData;
use Modules\Users\Domain\Entities\UserEntity;
use Modules\Users\Domain\Exceptions\EmailAlreadyTakenException;
use Modules\Users\Domain\Exceptions\UserNotFoundException;
use Modules\Users\Domain\Repositories\UserRepositoryInterface;
use Modules\Users\Domain\ValueObjects\Email;

final class UpdateUserUseCase
{
    public function __construct(private readonly UserRepositoryInterface $users)
    {
    }

    public function handle(UpdateUserData $data): UserEntity
    {
        $entity = $this->users->findById($data->id) ?? throw UserNotFoundException::withId($data->id);

        if ($data->email !== null) {
            $email = new Email($data->email);

            if ($this->users->existsByEmail($email->value(), excludingId: $entity->id())) {
                throw EmailAlreadyTakenException::forEmail($email->value());
            }

            $entity->changeEmail($email);
        }

        if ($data->name !== null) {
            $entity->rename($data->name);
        }

        if ($data->roles !== null) {
            $entity->assignRoles($data->roles);
        }

        if ($data->active !== null) {
            $data->active ? $entity->activate() : $entity->deactivate();
        }

        return $this->users->update($entity);
    }
}
