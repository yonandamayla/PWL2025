<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\RoleModel;

class RoleSeeder extends Seeder
{
    public function run()
    {
        RoleModel::create([
            'id' => 1,
            'name' => 'admin',
            'description' => 'Administrator with full access',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        RoleModel::create([
            'id' => 2,
            'name' => 'kasir',
            'description' => 'Kasir with limited access',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        RoleModel::create([
            'id' => 3,
            'name' => 'customer',
            'description' => 'customer with basic access',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}