<?php

declare(strict_types=1);

namespace Modules\Auth\Application\UseCases;

use Modules\Auth\Application\DTO\AuthResult;
use Modules\Auth\Application\DTO\LoginCredentials;
use Modules\Auth\Domain\Exceptions\AuthenticationFailedException;
use Modules\Auth\Domain\Repositories\UserAuthRepositoryInterface;
use Modules\Auth\Domain\ValueObjects\Email;
use Modules\Auth\Application\Ports\TokenIssuer;

/**
 * Cas d'usage : connexion via la stratégie "local" (email + mot de passe).
 *
 * Le use case orchestre le domaine (repository/entité) et ne connaît
 * de l'infrastructure que via l'interface TokenIssuer (port de sortie).
 */
final class AuthenticateLocalUserUseCase
{
    public function __construct(
        private readonly UserAuthRepositoryInterface $users,
        private readonly TokenIssuer $tokenIssuer,
    ) {
    }

    public function handle(LoginCredentials $credentials): AuthResult
    {
        $email = new Email($credentials->email);

        $user = $this->users->findByEmail($email);

        if ($user === null) {
            throw AuthenticationFailedException::invalidCredentials();
        }

        if (! $user->isActive()) {
            throw AuthenticationFailedException::inactiveAccount();
        }

        if (! $this->users->verifyPassword($user, $credentials->password)) {
            throw AuthenticationFailedException::invalidCredentials();
        }

        $token = $this->tokenIssuer->issueFor($user, $credentials->deviceName ?? 'api');

        return new AuthResult($user, $token);
    }
}
