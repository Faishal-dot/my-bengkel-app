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
    // Admin default
    \App\Models\User::create([
        'name' => 'Admin Bengkel',
        'email' => 'admin@bengkel.com',
        'password' => bcrypt('password'),
        'role' => 'admin',
    ]);

    // Customer dummy
    \App\Models\User::factory(5)->create();
}
}
