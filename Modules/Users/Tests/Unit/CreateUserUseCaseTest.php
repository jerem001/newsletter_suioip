<?php

declare(strict_types=1);

use Modules\Users\Application\DTO\CreateUserData;
use Modules\Users\Application\UseCases\CreateUserUseCase;
use Modules\Users\Domain\Entities\UserEntity;
use Modules\Users\Domain\Exceptions\EmailAlreadyTakenException;
use Modules\Users\Domain\Repositories\UserRepositoryInterface;
use Modules\Users\Domain\ValueObjects\Email;

it('crée un utilisateur quand l\'email est disponible', function (): void {
    $repository = Mockery::mock(UserRepositoryInterface::class);
    $repository->shouldReceive('existsByEmail')->once()->andReturn(false);
    $repository->shouldReceive('create')->once()->andReturnUsing(
        fn (UserEntity $entity) => new UserEntity(1, $entity->name(), $entity->email(), true, [])
    );

    $useCase = new CreateUserUseCase($repository);

    $user = $useCase->handle(new CreateUserData('Alice', 'alice@suioip.test', 'password123'));

    expect($user->id())->toBe(1)
        ->and($user->email())->toBeInstanceOf(Email::class);
});

it('lève une exception si l\'email est déjà pris', function (): void {
    $repository = Mockery::mock(UserRepositoryInterface::class);
    $repository->shouldReceive('existsByEmail')->once()->andReturn(true);

    $useCase = new CreateUserUseCase($repository);

    $useCase->handle(new CreateUserData('Alice', 'alice@suioip.test', 'password123'));
})->throws(EmailAlreadyTakenException::class);
