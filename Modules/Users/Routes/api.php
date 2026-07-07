<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use Modules\Users\Infrastructure\Http\Controllers\UserController;

Route::middleware('auth:sanctum')->prefix('v1')->name('users.')->group(function (): void {
    Route::apiResource('users', UserController::class)->parameters(['users' => 'user']);
});
