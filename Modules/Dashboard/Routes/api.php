<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use Modules\Dashboard\Infrastructure\Http\Controllers\DashboardController;

Route::middleware('auth:sanctum')->prefix('v1/dashboard')->name('dashboard.')->group(function (): void {
    Route::get('stats', [DashboardController::class, 'stats'])->name('stats');
});
