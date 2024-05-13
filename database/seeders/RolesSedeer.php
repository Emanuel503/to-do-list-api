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
        $roleAdmin  = Role::create(['name' => 'Admin']);
        $roleUSer   = Role::create(['name' => 'User']);

        Permission::create(['name' => 'admin.dashboard.index'])->syncRoles([$roleAdmin]);

        Permission::create(['name' => 'admin.users.index'])->syncRoles([$roleAdmin]);

        Permission::create(['name' => 'user.tasks.index'])->syncRoles([$roleAdmin, $roleUSer]);
        Permission::create(['name' => 'user.tasks.show'])->syncRoles([$roleAdmin, $roleUSer]);
        Permission::create(['name' => 'user.tasks.store'])->syncRoles([$roleAdmin, $roleUSer]);
        Permission::create(['name' => 'user.tasks.update'])->syncRoles([$roleAdmin, $roleUSer]);
        Permission::create(['name' => 'user.tasks.destroy'])->syncRoles([$roleAdmin, $roleUSer]);
        Permission::create(['name' => 'user.tasks.restore'])->syncRoles([$roleAdmin, $roleUSer]);
        Permission::create(['name' => 'user.tasks.share'])->syncRoles([$roleAdmin, $roleUSer]);
        Permission::create(['name' => 'user.tasks.deleteShare'])->syncRoles([$roleAdmin, $roleUSer]);

        Permission::create(['name' => 'user.categories.index'])->syncRoles([$roleAdmin, $roleUSer]);
    }
}
