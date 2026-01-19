<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Akun Admin
        \App\Models\User::create([
            'name' => 'Admin Bengkel',
            'email' => 'admin@bengkel.com',
            'password' => bcrypt('password'),
            'role' => 'admin',
        ]);

        // Panggil Seeder
        // PENTING: ProductSeeder harus di atas LayananSeeder agar relasi bundling bisa tersimpan
        $this->call([
            ProductSeeder::class,
            LayananSeeder::class,
        ]);
    }
}