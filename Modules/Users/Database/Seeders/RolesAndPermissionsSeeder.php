<?php

declare(strict_types=1);

namespace Modules\Users\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Users\Infrastructure\Persistence\Models\UserModel;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

final class RolesAndPermissionsSeeder extends Seeder
{
    public function run(): void
    {
        $permissions = [
            'users.viewAny',
            'users.view',
            'users.create',
            'users.update',
            'users.delete',
            'dashboard.view',
        ];

        foreach ($permissions as $permission) {
            Permission::findOrCreate($permission, 'api');
        }

        $admin = Role::findOrCreate('admin', 'api');
        $admin->syncPermissions($permissions);

        $editor = Role::findOrCreate('editor', 'api');
        $editor->syncPermissions(['dashboard.view']);

        if (! UserModel::query()->where('email', 'admin@suioip.test')->exists()) {
            $admin_user = UserModel::factory()->create([
                'name' => 'Super Admin',
                'email' => 'admin@suioip.test',
                'password' => bcrypt('password'),
            ]);

            $admin_user->assignRole($admin);
        }
    }
}
