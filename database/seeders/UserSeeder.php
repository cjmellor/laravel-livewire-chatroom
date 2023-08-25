<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    public function run()
    {
        User::factory()->create([
            'name' => 'Chris Mellor',
            'email' => 'chris@mellor.pizza',
            'password' => bcrypt('password'),
            'email_verified_at' => now(),
        ]);

        User::factory()->create([
            'name' => 'Kenny Omega',
            'email' => 'elite@aew.com',
            'password' => bcrypt('password'),
            'email_verified_at' => now(),
        ]);
    }
}
