<?php

declare(strict_types=1);

namespace Modules\Dashboard\Infrastructure\Providers;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use Modules\Dashboard\Domain\Repositories\DashboardStatsProviderInterface;
use Modules\Dashboard\Infrastructure\Persistence\Eloquent\EloquentDashboardStatsProvider;

final class DashboardServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(DashboardStatsProviderInterface::class, EloquentDashboardStatsProvider::class);
    }

    public function boot(): void
    {
        Route::middleware('api')
            ->prefix('api')
            ->group(module_path('Dashboard', 'Routes/api.php'));
    }
}
