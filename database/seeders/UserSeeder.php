<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Admin User
        User::create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'password' => Hash::make('password123'),
            'role' => 'admin',
            'phone' => '081234567890',
            'address' => 'Jl. Admin Street',
            'status' => 'active'
        ]);

        // Editor User
        User::create([
            'name' => 'Editor User',
            'email' => 'editor@example.com',
            'password' => Hash::make('password123'),
            'role' => 'editor',
            'phone' => '081234567891',
            'address' => 'Jl. Editor Street',
            'status' => 'active'
        ]);

        // Wartawan User
        User::create([
            'name' => 'Wartawan User',
            'email' => 'wartawan@example.com',
            'password' => Hash::make('password123'),
            'role' => 'wartawan',
            'phone' => '081234567892',
            'address' => 'Jl. Wartawan Street',
            'status' => 'active'
        ]);
    }
}
