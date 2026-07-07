<?php

declare(strict_types=1);

namespace Modules\Dashboard\Application\UseCases;

use Modules\Dashboard\Application\DTO\DashboardStats;
use Modules\Dashboard\Domain\Repositories\DashboardStatsProviderInterface;

final class GetDashboardStatsUseCase
{
    public function __construct(private readonly DashboardStatsProviderInterface $statsProvider)
    {
    }

    public function handle(): DashboardStats
    {
        return $this->statsProvider->getStats();
    }
}
