<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\RoleModel; 

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        $roles = [
            [
                'name' => 'Admin',
                'description' => 'Administrator with full access to all features'
            ],
            [
                'name' => 'Manager',
                'description' => 'Manager with access to inventory and reports'
            ],
            [
                'name' => 'Cashier',
                'description' => 'Cashier with access to orders only'
            ],
        ];

        foreach ($roles as $role) {
            RoleModel::create($role);  
        }
    }
}