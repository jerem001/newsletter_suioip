<?php

declare(strict_types=1);

namespace Modules\Dashboard\Infrastructure\Persistence\Eloquent;

use Modules\Dashboard\Application\DTO\DashboardStats;
use Modules\Dashboard\Domain\Repositories\DashboardStatsProviderInterface;
use Modules\Users\Infrastructure\Persistence\Models\UserModel;

final class EloquentDashboardStatsProvider implements DashboardStatsProviderInterface
{
    public function getStats(): DashboardStats
    {
        $total = UserModel::query()->count();
        $active = UserModel::query()->where('is_active', true)->count();

        $byStrategy = UserModel::query()
            ->selectRaw('auth_strategy, count(*) as total')
            ->groupBy('auth_strategy')
            ->pluck('total', 'auth_strategy')
            ->toArray();

        return new DashboardStats(
            totalUsers: $total,
            activeUsers: $active,
            inactiveUsers: $total - $active,
            usersByStrategy: $byStrategy,
        );
    }
}
