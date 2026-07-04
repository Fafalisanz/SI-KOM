<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        // 1. Jalankan Seeder untuk Role dan User utama dulu
        $this->call([
            RoleSeeder::class,
            UserSeeder::class,
        ]); 

        // 2. Baru jalankan Factory jika ingin menambah user testing bawaan Laravel
        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'role_id' => 1, // Wajib ditambah 1 (Admin), 2 (Staff), atau 3 (Manager) karena database kita membutuhkannya
        ]);
    }
}