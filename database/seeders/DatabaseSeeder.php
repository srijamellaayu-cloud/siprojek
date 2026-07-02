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
        // User::factory(10)->create();

        User::factory()->create([
            'name' => 'Administrasi User',
            'email' => 'admin@example.com',
            'password' => bcrypt('password'),
            'role' => 'administrasi',
        ]);

        User::factory()->create([
            'name' => 'Keuangan User',
            'email' => 'keuangan@example.com',
            'password' => bcrypt('password'),
            'role' => 'keuangan',
        ]);

        User::factory()->create([
            'name' => 'Eksekutif User',
            'email' => 'eksekutif@example.com',
            'password' => bcrypt('password'),
            'role' => 'eksekutif',
        ]);
    }
}
