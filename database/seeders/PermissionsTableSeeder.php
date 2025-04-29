<?php

namespace Database\Seeders;

use App\Models\Permission;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PermissionsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $permissions = [
            'get_all_users',
            'get_all_admins',
            'get_all_regular_users',
            'add_user',
            'delete_user',
            'update_activation_user',

            'user_get_all_posts_with_their_comments',
            'user_add_comment_on_post',
            'user_get_all_my_posts_with_their_comments',
            'user_add_post',
            'user_update_my_post',
            'user_delete_my_post',
            'user_get_user_profile',
        ];

        foreach ($permissions as $permission) {
            Permission::create(['title'=>$permission]);
        }
    }
}
