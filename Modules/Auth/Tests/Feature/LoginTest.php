<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Hash;
use Modules\Users\Infrastructure\Persistence\Models\UserModel;

it('permet à un utilisateur de se connecter via /api/v1/auth/login', function (): void {
    UserModel::factory()->create([
        'email' => 'admin@suioip.test',
        'password' => Hash::make('password'),
        'is_active' => true,
    ]);

    $response = $this->postJson('/api/v1/auth/login', [
        'email' => 'admin@suioip.test',
        'password' => 'password',
    ]);

    $response->assertOk()
        ->assertJsonStructure(['user' => ['id', 'name', 'email', 'roles'], 'token', 'token_type']);
});

it('refuse un mot de passe erroné avec un 422', function (): void {
    UserModel::factory()->create([
        'email' => 'admin@suioip.test',
        'password' => Hash::make('password'),
    ]);

    $response = $this->postJson('/api/v1/auth/login', [
        'email' => 'admin@suioip.test',
        'password' => 'mauvais-mot-de-passe',
    ]);

    $response->assertStatus(422);
});

it('protège /api/v1/auth/me derrière sanctum', function (): void {
    $this->getJson('/api/v1/auth/me')->assertStatus(401);
});
