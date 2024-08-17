<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserRoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user = User::updateOrCreate(
            [
                'name' => 'admin',
                'email' => 'admin@admin.ru',
                'phone' => '77777777777',
                'password' => Hash::make('12344321'),
                'created_at' => now(),
                'updated_at' => now(),
            ],
        );
        $roleAdmin = Role::updateOrCreate([
            'name' => Role::ADMIN,
        ]);
        $user->roles()->sync([
            $roleAdmin->id,
        ]);
    }
}
