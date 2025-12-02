<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

    \App\Models\User::create([
    'name' => 'Admin User',
    'email' => 'admin@demo.com',
    'password' => bcrypt('password'),
    'role' => 'admin',
    ]);

    \App\Models\User::create([
    'name' => 'Owner User',
    'email' => 'owner@demo.com',
    'password' => bcrypt('password'),
    'role' => 'owner',
    ]);
    }
}
