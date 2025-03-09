<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create roles if they do not exist
        if (!Role::where('name', 'admin')->exists()) {
            Role::create(['name' => 'admin', 'guard_name' => 'web']);
        }

        if (!Role::where('name', 'user')->exists()) {
            Role::create(['name' => 'user', 'guard_name' => 'web']);
        }

        // Create permissions if they do not exist
        if (!Permission::where('name', 'edit articles')->exists()) {
            Permission::create(['name' => 'edit articles']);
        }

        if (!Permission::where('name', 'delete articles')->exists()) {
            Permission::create(['name' => 'delete articles']);
        }

        if (!Permission::where('name', 'read_build')->exists()) {
            Permission::create(['name' => 'read_build']);
        }

        if (!Permission::where('name', 'create_build')->exists()) {
            Permission::create(['name' => 'create_build']);
        }

        if (!Permission::where('name', 'delete_build')->exists()) {
            Permission::create(['name' => 'delete_build']);
        }


        // Assign permissions to roles
        $adminRole = Role::findByName('admin');
        $userRole = Role::findByName('user');

        $adminRole->givePermissionTo(['edit articles', 'delete articles']);
        $userRole->givePermissionTo(['edit articles', 'read_build', 'create_build', 'delete_build']);

    }
}
