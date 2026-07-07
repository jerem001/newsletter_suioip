<?php

declare(strict_types=1);

namespace Modules\Dashboard\Infrastructure\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller;
use Modules\Dashboard\Application\UseCases\GetDashboardStatsUseCase;

/**
 * @OA\Tag(name="Dashboard", description="Indicateurs et vue d'ensemble du back-office")
 */
final class DashboardController extends Controller
{
    public function __construct(private readonly GetDashboardStatsUseCase $getDashboardStats)
    {
    }

    /**
     * @OA\Get(
     *     path="/api/v1/dashboard/stats",
     *     tags={"Dashboard"},
     *     summary="Statistiques globales (utilisateurs actifs, par stratégie d'auth...)",
     *     security={{"sanctum":{}}},
     *     @OA\Response(response=200, description="Statistiques")
     * )
     */
    public function stats(): JsonResponse
    {
        $stats = $this->getDashboardStats->handle();

        return response()->json([
            'total_users' => $stats->totalUsers,
            'active_users' => $stats->activeUsers,
            'inactive_users' => $stats->inactiveUsers,
            'users_by_strategy' => $stats->usersByStrategy,
        ]);
    }
}
