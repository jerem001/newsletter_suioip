<?php

declare(strict_types=1);

namespace Modules\Dashboard\Application\DTO;

final class DashboardStats
{
    public function __construct(
        public readonly int $totalUsers,
        public readonly int $activeUsers,
        public readonly int $inactiveUsers,
        public readonly array $usersByStrategy,
    ) {
    }
}
