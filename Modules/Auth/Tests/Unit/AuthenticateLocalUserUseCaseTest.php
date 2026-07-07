<?php

declare(strict_types=1);

use Modules\Auth\Application\DTO\LoginCredentials;
use Modules\Auth\Application\Ports\TokenIssuer;
use Modules\Auth\Application\UseCases\AuthenticateLocalUserUseCase;
use Modules\Auth\Domain\Entities\AuthenticatedUser;
use Modules\Auth\Domain\Exceptions\AuthenticationFailedException;
use Modules\Auth\Domain\Repositories\UserAuthRepositoryInterface;
use Modules\Auth\Domain\ValueObjects\AuthStrategy;
use Modules\Auth\Domain\ValueObjects\Email;

function makeAuthenticatedUser(bool $active = true): AuthenticatedUser
{
    return new AuthenticatedUser(
        id: 1,
        name: 'Jean Dupont',
        email: new Email('jean@example.org'),
        strategy: AuthStrategy::Local,
        roles: ['admin'],
        active: $active,
    );
}

it('authentifie un utilisateur avec des identifiants valides', function (): void {
    $users = Mockery::mock(UserAuthRepositoryInterface::class);
    $users->shouldReceive('findByEmail')->once()->andReturn(makeAuthenticatedUser());
    $users->shouldReceive('verifyPassword')->once()->andReturn(true);

    $tokenIssuer = Mockery::mock(TokenIssuer::class);
    $tokenIssuer->shouldReceive('issueFor')->once()->andReturn('plain-text-token');

    $useCase = new AuthenticateLocalUserUseCase($users, $tokenIssuer);

    $result = $useCase->handle(new LoginCredentials('jean@example.org', 'password123'));

    expect($result->token)->toBe('plain-text-token')
        ->and($result->user->email()->value())->toBe('jean@example.org');
});

it('rejette un mot de passe invalide', function (): void {
    $users = Mockery::mock(UserAuthRepositoryInterface::class);
    $users->shouldReceive('findByEmail')->once()->andReturn(makeAuthenticatedUser());
    $users->shouldReceive('verifyPassword')->once()->andReturn(false);

    $tokenIssuer = Mockery::mock(TokenIssuer::class);

    $useCase = new AuthenticateLocalUserUseCase($users, $tokenIssuer);

    $useCase->handle(new LoginCredentials('jean@example.org', 'wrong-password'));
})->throws(AuthenticationFailedException::class);

it('rejette un compte désactivé', function (): void {
    $users = Mockery::mock(UserAuthRepositoryInterface::class);
    $users->shouldReceive('findByEmail')->once()->andReturn(makeAuthenticatedUser(active: false));

    $tokenIssuer = Mockery::mock(TokenIssuer::class);

    $useCase = new AuthenticateLocalUserUseCase($users, $tokenIssuer);

    $useCase->handle(new LoginCredentials('jean@example.org', 'password123'));
})->throws(AuthenticationFailedException::class);
