<?php

return [
    'namespace' => 'Modules',

    'stubs' => [
        'enabled' => true,
        'path' => base_path('.stubs'),
    ],

    'paths' => [
        'modules' => base_path('Modules'),
        'assets' => public_path('modules'),
        'migration' => base_path('database/migrations'),
        'generator' => [
            'domain' => ['path' => 'Domain', 'generate' => true],
            'application' => ['path' => 'Application', 'generate' => true],
            'infrastructure' => ['path' => 'Infrastructure', 'generate' => true],
            'config' => ['path' => 'Config', 'generate' => true],
            'routes' => ['path' => 'Routes', 'generate' => true],
            'migrations' => ['path' => 'Database/Migrations', 'generate' => true],
            'factories' => ['path' => 'Database/Factories', 'generate' => true],
            'seeders' => ['path' => 'Database/Seeders', 'generate' => true],
            'test' => ['path' => 'Tests', 'generate' => true],
        ],
    ],

    'scan' => [
        'enabled' => false,
    ],

    'composer' => [
        'vendor' => 'suioip',
        'author' => [
            'name' => 'Suioip Contributors',
            'email' => 'dev@suioip.test',
        ],
    ],

    /*
    | Ordre de chargement des modules au boot. Les modules listés ici sont
    | chargés en priorité (ex : Auth doit être dispo avant Users).
    */
    'activators' => [
        'file' => [
            'class' => \Nwidart\Modules\Activators\FileActivator::class,
            'statuses-file' => base_path('modules_statuses.json'),
            'cache-key' => 'activator.installed',
            'cache-lifetime' => 604800,
        ],
    ],
];
