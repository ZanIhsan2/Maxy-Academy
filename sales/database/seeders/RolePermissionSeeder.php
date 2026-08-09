<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolePermissionSeeder extends Seeder
{
    public function run(): void
    {
        // Permission untuk siklus Sales & Purchasing
        Permission::firstOrCreate(['name' => 'manage-master']);
        Permission::firstOrCreate(['name' => 'manage-purchase']);
        Permission::firstOrCreate(['name' => 'manage-sales']);

        // Buat Role Admin & Assign Permission
        $admin = Role::firstOrCreate(['name' => 'Admin']);
        $admin->givePermissionTo(Permission::all());

        // Buat Role Purchasing Staff
        $purchasing = Role::firstOrCreate(['name' => 'Purchasing']);
        $purchasing->givePermissionTo(['manage-master', 'manage-purchase']);
    }
}
