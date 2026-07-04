<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Akun Admin (role_id = 1)
        User::create([
            'name' => 'Fasikhul Admin',
            'email' => 'admin@telkomsel.com',
            'password' => Hash::make('password123'),
            'role_id' => 1,
        ]);

        // Akun Staff (role_id = 2)
        User::create([
            'name' => 'Issan Staff',
            'email' => 'staff@telkomsel.com',
            'password' => Hash::make('password123'),
            'role_id' => 2,
        ]);

        // Akun Manager (role_id = 3)
        User::create([
            'name' => 'Budi Manager',
            'email' => 'manager@telkomsel.com',
            'password' => Hash::make('password123'),
            'role_id' => 3,
        ]);
    }
}