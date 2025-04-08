<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ItemTypeModel;

class ItemTypeSeeder extends Seeder
{
    public function run()
    {
        ItemTypeModel::create([
            'id' => 1,
            'name' => 'food-beverage',
            'description' => 'Food and beverage items',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        ItemTypeModel::create([
            'id' => 2,
            'name' => 'beauty-health',
            'description' => 'Beauty and health products',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        ItemTypeModel::create([
            'id' => 3,
            'name' => 'home-care',
            'description' => 'Home care products',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        ItemTypeModel::create([
            'id' => 4,
            'name' => 'baby-kid',
            'description' => 'Baby and kid products',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}