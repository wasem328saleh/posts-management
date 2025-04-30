<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('users')->delete();
        $admin=User::create([
            'name' => 'Levant Task',
            'email' => 'levanttask@gmail.com',
            'password' => Hash::make('12345678'),
            'is_active' => true
        ]);
        $admin->roles()->sync([1,2]);
        $admin->image_profile()->create([
            'url'=>'user_profile_default.png'
        ]);
        User::factory()->count(2)->create()->each(function ($user) {
            $user->image_profile()->create([
                'url'=>'user_profile_default.png'
            ]);
        });


    }
}
