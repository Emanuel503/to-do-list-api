<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolesSedeer extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $roleAdmin  = Role::create(['name' => 'Admin', 'guard_name' => 'api']);
        $roleUSer   = Role::create(['name' => 'User', 'guard_name' => 'api']);

        Permission::create(['name' => 'admin.dashboard.index', 'guard_name' => 'api'])->syncRoles([$roleAdmin]);

        Permission::create(['name' => 'admin.users.index', 'guard_name' => 'api'])->syncRoles([$roleAdmin]);

        Permission::create(['name' => 'user.tasks.index', 'guard_name' => 'api'])->syncRoles([$roleAdmin, $roleUSer]);
        Permission::create(['name' => 'user.tasks.show', 'guard_name' => 'api'])->syncRoles([$roleAdmin, $roleUSer]);
        Permission::create(['name' => 'user.tasks.store', 'guard_name' => 'api'])->syncRoles([$roleAdmin, $roleUSer]);
        Permission::create(['name' => 'user.tasks.update', 'guard_name' => 'api'])->syncRoles([$roleAdmin, $roleUSer]);
        Permission::create(['name' => 'user.tasks.destroy', 'guard_name' => 'api'])->syncRoles([$roleAdmin, $roleUSer]);
        Permission::create(['name' => 'user.tasks.restore', 'guard_name' => 'api'])->syncRoles([$roleAdmin, $roleUSer]);
        Permission::create(['name' => 'user.tasks.share', 'guard_name' => 'api'])->syncRoles([$roleAdmin, $roleUSer]);
        Permission::create(['name' => 'user.tasks.deleteShare', 'guard_name' => 'api'])->syncRoles([$roleAdmin, $roleUSer]);

        Permission::create(['name' => 'user.categories.index', 'guard_name' => 'api'])->syncRoles([$roleAdmin, $roleUSer]);
    }
}
