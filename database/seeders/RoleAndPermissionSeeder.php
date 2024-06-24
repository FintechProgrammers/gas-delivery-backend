<?php

namespace Database\Seeders;

use App\Models\Admin;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class RoleAndPermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Delete all assigned roles and permissions for users
        Admin::all()->each(function ($user) {
            $user->roles()->sync([]);
        });

        User::all()->each(function ($user) {
            $user->roles()->sync([]);
        });

        // Delete all existing roles
        Role::query()->delete();

        // Delete all existing permissions
        Permission::query()->delete();

        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        Permission::create(['name' => 'manage user', 'guard_name' => 'admin']);
        Permission::create(['name' => 'banned user', 'guard_name' => 'admin']);
        Permission::create(['name' => 'manage ticket', 'guard_name' => 'admin']);
        Permission::create(['name' => 'reply ticket', 'guard_name' => 'admin']);
        Permission::create(['name' => 'delete ticket', 'guard_name' => 'admin']);
        Permission::create(['name' => 'manage settings', 'guard_name' => 'admin']);
        Permission::create(['name' => 'payment settings', 'guard_name' => 'admin']);
        Permission::create(['name' => 'manage transactions', 'guard_name' => 'admin']);

        $permissions = Permission::get();

        $role = Role::create(['uuid' => Str::uuid()->toString(), 'name' => 'super admin', 'guard_name' => 'admin']);

        $defaultRole = Role::create(['uuid' => Str::uuid()->toString(), 'name' => 'default', 'guard_name' => 'admin']);

        $defaultRole->syncPermissions([
            'manage user'
        ]);

        $role->syncPermissions([$permissions->map(fn ($permission) => $permission->name)]);

        $admin = Admin::first();

        $admin->assignRole($role);
    }
}
