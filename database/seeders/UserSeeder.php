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
            ['name' => 'Admin User', 'password' => 'admin@123', 'role' => 'admin']
        );

        User::updateOrCreate(
            ['email' => 'production@example.com'],
            ['name' => 'Production User', 'password' => 'production@123', 'role' => 'production']
        );

        User::updateOrCreate(
            ['email' => 'viewer@example.com'],
            ['name' => 'Viewer User', 'password' => 'viewer@123', 'role' => 'viewer']
        );
    }
}
