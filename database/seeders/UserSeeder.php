<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        User::create([
            'name' => 'Super Admin',
            'email' => 'admin@gmail.com',
            'password' => Hash::make('password'),
            'phone' => '+99362240774',
            'address' => 'Admin Headquarters',
            'role' => 'admin',
        ]);

        User::create([
            'name' => 'Test Client',
            'email' => 'client@gmail.com',
            'password' => Hash::make('password'),
            'phone' => '+99361111111',
            'address' => 'Ashgabat, Turkmenistan',
            'role' => 'client',
        ]);

        User::factory(10)->create();
    }
}
