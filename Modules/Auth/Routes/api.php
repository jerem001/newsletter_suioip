<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use Modules\Auth\Infrastructure\Http\Controllers\AuthController;
use Modules\Auth\Infrastructure\Http\Controllers\CasAuthController;
use Modules\Auth\Infrastructure\Http\Controllers\LdapAuthController;

Route::prefix('v1/auth')->name('auth.')->group(function (): void {
    // Stratégie locale
    Route::post('login', [AuthController::class, 'login'])->name('login');
    Route::post('logout', [AuthController::class, 'logout'])
        ->middleware('auth:sanctum')
        ->name('logout');
    Route::get('me', [AuthController::class, 'me'])
        ->middleware('auth:sanctum')
        ->name('me');

    // Stratégie CAS
    Route::get('cas/login', [CasAuthController::class, 'login'])->name('cas.login');
    Route::get('cas/logout', [CasAuthController::class, 'logout'])->name('cas.logout');

    // Stratégie LDAP
    Route::post('ldap/login', [LdapAuthController::class, 'login'])->name('ldap.login');
});
