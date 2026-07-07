<?php

declare(strict_types=1);

namespace Modules\Users\Infrastructure\Providers;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use Modules\Users\Domain\Repositories\UserRepositoryInterface;
use Modules\Users\Infrastructure\Persistence\Eloquent\EloquentUserRepository;

final class UsersServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(UserRepositoryInterface::class, EloquentUserRepository::class);
    }

    public function boot(): void
    {
        Route::middleware('api')
            ->prefix('api')
            ->group(module_path('Users', 'Routes/api.php'));

        $this->loadMigrationsFrom(module_path('Users', 'Database/Migrations'));

        $this->publishes([
            module_path('Users', 'Database/Seeders') => database_path('seeders/vendor/users'),
        ], 'users-seeders');
    }
}
