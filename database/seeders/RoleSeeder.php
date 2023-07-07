<?php

namespace Database\Seeders;

use App\Enums\PermissionEnum;
use App\Enums\RoleEnum;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        foreach (PermissionEnum::cases() as $permission) {
            Permission::create(['name' => $permission->value]);
        }

        foreach (RoleEnum::cases() as $role) {
            $roleDB = Role::create(['name' => $role->value]);

            if ($role === RoleEnum::SUPER_ADMIN) {
                $roleDB->syncPermissions(array_column(PermissionEnum::cases(), 'value'));
            }
        }
    }
}
