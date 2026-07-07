<?php

declare(strict_types=1);

namespace Modules\Auth\Infrastructure\Providers;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use Modules\Auth\Application\Ports\TokenIssuer;
use Modules\Auth\Domain\Repositories\UserAuthRepositoryInterface;
use Modules\Auth\Infrastructure\Persistence\Eloquent\EloquentUserAuthRepository;
use Modules\Auth\Infrastructure\Security\SanctumTokenIssuer;

final class AuthServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        // Bindings hexagonaux : port (interface du domaine/application)
        // -> adapter (implémentation infrastructure).
        $this->app->bind(UserAuthRepositoryInterface::class, EloquentUserAuthRepository::class);
        $this->app->bind(TokenIssuer::class, SanctumTokenIssuer::class);

        $this->mergeConfigFrom(module_path('Auth', 'Config/config.php'), 'auth_module');
    }

    public function boot(): void
    {
        Route::middleware('api')
            ->prefix('api')
            ->group(module_path('Auth', 'Routes/api.php'));

        $this->loadMigrationsFrom(module_path('Auth', 'Database/Migrations'));
    }
}
