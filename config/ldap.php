<?php

return [
    'default' => env('LDAP_CONNECTION', 'default'),

    'enabled' => env('LDAP_ENABLED', false),

    'logging' => [
        'enabled' => env('LDAP_LOGGING', true),
        'channel' => env('LDAP_LOGGING_CHANNEL', 'stack'),
        'level' => env('LDAP_LOGGING_LEVEL', 'info'),
    ],

    'connections' => [
        'default' => [
            'hosts' => [env('LDAP_HOST', 'ldap.example.org')],
            'username' => env('LDAP_USERNAME'),
            'password' => env('LDAP_PASSWORD'),
            'port' => env('LDAP_PORT', 389),
            'base_dn' => env('LDAP_BASE_DN'),
            'timeout' => env('LDAP_TIMEOUT', 5),
            'use_ssl' => env('LDAP_USE_SSL', false),
            'use_tls' => env('LDAP_USE_TLS', true),
        ],
    ],
];
