<?php

declare(strict_types=1);

use Modules\Users\Infrastructure\Persistence\Models\UserModel;

it('retourne les statistiques du dashboard pour un utilisateur authentifié', function (): void {
    $actor = UserModel::factory()->create();
    UserModel::factory()->count(2)->create();
    UserModel::factory()->inactive()->create();

    $this->actingAs($actor, 'api')
        ->getJson('/api/v1/dashboard/stats')
        ->assertOk()
        ->assertJsonStructure(['total_users', 'active_users', 'inactive_users', 'users_by_strategy'])
        ->assertJsonPath('total_users', 4)
        ->assertJsonPath('active_users', 3)
        ->assertJsonPath('inactive_users', 1);
});

it('refuse l\'accès sans authentification', function (): void {
    $this->getJson('/api/v1/dashboard/stats')->assertStatus(401);
});
