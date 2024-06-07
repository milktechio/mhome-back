<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $roles = [
            'administracion' => [
            ],
            'soporte' => [
            ],
            'usuario' => [
            ],
        ];

        foreach ($roles as $roleName => $permissions) {
            $role = Role::create(['name' => $roleName, 'edit' => 0]);

            foreach ($permissions as $capabilitie) {
                $permission = Permission::create(['name' => $capabilitie]);
                $role->givePermissionTo($permission);
            }
        }
    }
}
