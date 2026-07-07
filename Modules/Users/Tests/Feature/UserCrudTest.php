<?php

declare(strict_types=1);

use Modules\Users\Infrastructure\Persistence\Models\UserModel;
use Spatie\Permission\Models\Permission;

function actingAsUserWithPermissions(array $permissions): UserModel
{
    foreach ($permissions as $permission) {
        Permission::findOrCreate($permission, 'api');
    }

    $user = UserModel::factory()->create();
    $user->givePermissionTo($permissions);

    return $user;
}

it('liste les utilisateurs de façon paginée', function (): void {
    $actor = actingAsUserWithPermissions(['users.viewAny']);
    UserModel::factory()->count(3)->create();

    $this->actingAs($actor, 'api')
        ->getJson('/api/v1/users')
        ->assertOk()
        ->assertJsonStructure(['data', 'meta' => ['total', 'per_page', 'current_page']]);
});

it('crée un utilisateur', function (): void {
    $actor = actingAsUserWithPermissions(['users.create']);

    $this->actingAs($actor, 'api')
        ->postJson('/api/v1/users', [
            'name' => 'Alice Martin',
            'email' => 'alice@suioip.test',
            'password' => 'password123',
        ])
        ->assertCreated()
        ->assertJsonPath('email', 'alice@suioip.test');
});

it('refuse la création si email déjà pris', function (): void {
    $actor = actingAsUserWithPermissions(['users.create']);
    UserModel::factory()->create(['email' => 'alice@suioip.test']);

    $this->actingAs($actor, 'api')
        ->postJson('/api/v1/users', [
            'name' => 'Alice Bis',
            'email' => 'alice@suioip.test',
        ])
        ->assertStatus(422);
});

it('supprime (soft delete) un utilisateur', function (): void {
    $actor = actingAsUserWithPermissions(['users.delete']);
    $target = UserModel::factory()->create();

    $this->actingAs($actor, 'api')
        ->deleteJson("/api/v1/users/{$target->id}")
        ->assertStatus(204);

    expect(UserModel::query()->find($target->id))->toBeNull()
        ->and(UserModel::withTrashed()->find($target->id))->not->toBeNull();
});
