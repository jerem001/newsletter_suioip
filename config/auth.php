<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Stratégie d'authentification active
    |--------------------------------------------------------------------------
    | "local" | "cas" | "ldap" — pilotée par AUTH_DEFAULT_STRATEGY.
    | Le module Auth choisit dynamiquement le bon Guard/Provider via
    | Modules\Auth\Infrastructure\Providers\AuthServiceProvider.
    */
    'strategy' => env('AUTH_DEFAULT_STRATEGY', 'local'),

    'defaults' => [
        'guard' => 'api',
        'passwords' => 'users',
    ],

    'guards' => [
        'web' => [
            'driver' => 'session',
            'provider' => 'users',
        ],

        // Guard API "classique" (token Sanctum) — utilisé par la stratégie "local"
        'api' => [
            'driver' => 'sanctum',
            'provider' => 'users',
        ],

        // Guard CAS — délègue la validation du ticket au serveur CAS central
        'cas' => [
            'driver' => 'cas',
            'provider' => 'users',
        ],

        // Guard LDAP — bind + vérif credentials contre l'annuaire
        'ldap' => [
            'driver' => 'ldap',
            'provider' => 'ldap_users',
        ],
    ],

    'providers' => [
        'users' => [
            'driver' => 'eloquent',
            'model' => Modules\Users\Infrastructure\Persistence\Models\UserModel::class,
        ],

        // Provider hybride : authentifie contre LDAP puis synchronise
        // (jit-provisioning) vers la table users locale.
        'ldap_users' => [
            'driver' => 'ldap',
            'model' => LdapRecord\Models\OpenLDAP\User::class,
            'rules' => [],
            'database' => [
                'model' => Modules\Users\Infrastructure\Persistence\Models\UserModel::class,
                'sync_passwords' => false,
                'sync_attributes' => [
                    'name' => 'cn',
                    'email' => 'mail',
                ],
                'sync_existing' => ['email' => 'mail'],
            ],
        ],
    ],

    'passwords' => [
        'users' => [
            'provider' => 'users',
            'table' => 'password_reset_tokens',
            'expire' => 60,
            'throttle' => 60,
        ],
    ],

    'password_timeout' => 10800,
];
