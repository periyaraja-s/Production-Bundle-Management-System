<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'admin@example.com'],
            ['name' => 'Admin User', 'password' => 'password', 'role' => 'admin']
        );

        User::updateOrCreate(
            ['email' => 'production@example.com'],
            ['name' => 'Production User', 'password' => 'password', 'role' => 'production']
        );

        User::updateOrCreate(
            ['email' => 'viewer@example.com'],
            ['name' => 'Viewer User', 'password' => 'password', 'role' => 'viewer']
        );
    }
}
