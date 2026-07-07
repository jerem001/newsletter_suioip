<?php

return [
    'name' => 'Auth',

    // Stratégies disponibles et activables indépendamment de la stratégie
    // "par défaut" définie dans config/auth.php (strategy).
    'strategies' => [
        'local' => true,
        'cas' => env('CAS_ENABLED', false),
        'ldap' => env('LDAP_ENABLED', false),
    ],
];
