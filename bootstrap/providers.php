<?php

return [
    App\Providers\AppServiceProvider::class,

    // Packages tiers
    Nwidart\Modules\LaravelModulesServiceProvider::class,
    Spatie\Permission\PermissionServiceProvider::class,
    L5Swagger\L5SwaggerServiceProvider::class,
    Subfission\Cas\CasServiceProvider::class,
    LdapRecord\Laravel\LdapAuthServiceProvider::class,

    // Modules métier (DDD / hexagonal) — v0.1
    Modules\Auth\Infrastructure\Providers\AuthServiceProvider::class,
    Modules\Users\Infrastructure\Providers\UsersServiceProvider::class,
    Modules\Dashboard\Infrastructure\Providers\DashboardServiceProvider::class,
];
