<?php

declare(strict_types=1);

namespace Modules\Dashboard\Domain\Repositories;

use Modules\Dashboard\Application\DTO\DashboardStats;

/**
 * Port de sortie : le module Dashboard ne connaît que ce contrat pour
 * obtenir des indicateurs ; l'implémentation interroge le module Users
 * (ou toute autre source à l'avenir : campagnes, envois, etc.).
 */
interface DashboardStatsProviderInterface
{
    public function getStats(): DashboardStats;
}
