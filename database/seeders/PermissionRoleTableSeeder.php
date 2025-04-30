<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\Role;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PermissionRoleTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $all_permissions = Permission::all();
        $admin_permissions=$all_permissions->filter(function ($permission) {
            return !str_starts_with($permission->title, 'user_');
        });
        $user_permissions = $all_permissions->filter(function ($permission) {
            return str_starts_with($permission->title, 'user_');
        });

        Role::findOrFail(1)->permissions()->sync($admin_permissions->pluck('id'));
        Role::findOrFail(2)->permissions()->sync($user_permissions->pluck('id'));

    }
}
