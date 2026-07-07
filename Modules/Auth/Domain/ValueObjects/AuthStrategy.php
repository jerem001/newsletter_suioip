<?php

declare(strict_types=1);

namespace Modules\Auth\Domain\ValueObjects;

enum AuthStrategy: string
{
    case Local = 'local';
    case Cas = 'cas';
    case Ldap = 'ldap';

    public function label(): string
    {
        return match ($this) {
            self::Local => 'Compte local',
            self::Cas => 'CAS (SSO)',
            self::Ldap => 'Annuaire LDAP',
        };
    }
}
