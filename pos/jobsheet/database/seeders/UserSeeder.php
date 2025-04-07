<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\UserModel;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        UserModel::create([
            'role_id' => 1, // Admin
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'password' => Hash::make('password'),
            'profile_picture' => null,
        ]);
        
        UserModel::create([
            'role_id' => 2, // Manager
            'name' => 'Manager User',
            'email' => 'manager@example.com',
            'password' => Hash::make('password'),
            'profile_picture' => null,
        ]);
        
        UserModel::create([
            'role_id' => 3, // Cashier
            'name' => 'Cashier User',
            'email' => 'cashier@example.com',
            'password' => Hash::make('password'),
            'profile_picture' => null,
        ]);
    }
}