<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\UserModel;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run()
    {
        UserModel::create([
            'id' => 1,
            'role_id' => 1, // admin
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'password' => Hash::make('password123'),
            'profile_picture' => null,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        UserModel::create([
            'id' => 2,
            'role_id' => 2, // kasir
            'name' => 'Kasir',
            'email' => 'kasir@example.com',
            'password' => Hash::make('password123'),
            'profile_picture' => null,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        UserModel::create([
            'id' => 3,
            'role_id' => 3, // pembeli
            'name' => 'Customer-1',
            'email' => 'customer1@example.com',
            'password' => Hash::make('password123'),
            'profile_picture' => null,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}