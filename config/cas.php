<?php

return [
    'enabled' => env('CAS_ENABLED', false),

    'cas_hostname' => env('CAS_HOSTNAME', ''),
    'cas_real_hosts' => env('CAS_REAL_HOSTS', null),
    'cas_control_session' => true,
    'cas_context' => env('CAS_URI', '/cas'),
    'cas_port' => (int) env('CAS_PORT', 443),
    'cas_version' => env('CAS_VERSION', '3.0'),
    'cas_verify_ssl' => env('CAS_VERIFY_SSL', true),
    'cas_cert_path' => env('CAS_CERT_PATH', ''),

    'cas_login_route' => 'auth.cas.login',
    'cas_logout_route' => 'auth.cas.logout',

    // Mapping des attributs renvoyés par le CAS vers le modèle User local
    'cas_attribute_mapping' => [
        'name' => 'cn',
        'email' => 'mail',
    ],
];
