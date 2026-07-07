<?php

declare(strict_types=1);

namespace Modules\Auth\Application\UseCases;

use Modules\Auth\Application\DTO\AuthResult;
use Modules\Auth\Domain\Exceptions\AuthenticationFailedException;
use Modules\Auth\Domain\Repositories\UserAuthRepositoryInterface;
use Modules\Auth\Domain\ValueObjects\Email;
use Modules\Auth\Application\Ports\TokenIssuer;

/**
 * Cas d'usage commun aux stratégies "cas" et "ldap" : l'identité a déjà
 * été vérifiée par le fournisseur externe (ticket CAS validé, ou bind
 * LDAP réussi) ; on ne fait ici que du JIT provisioning + émission de
 * token applicatif.
 */
final class AuthenticateExternalUserUseCase
{
    public function __construct(
        private readonly UserAuthRepositoryInterface $users,
        private readonly TokenIssuer $tokenIssuer,
    ) {
    }

    public function handle(string $email, string $name, array $roles = []): AuthResult
    {
        $emailVo = new Email($email);

        $user = $this->users->upsertFromExternalAttributes($emailVo, $name, $roles);

        if (! $user->isActive()) {
            throw AuthenticationFailedException::inactiveAccount();
        }

        $token = $this->tokenIssuer->issueFor($user, 'sso');

        return new AuthResult($user, $token);
    }
}
