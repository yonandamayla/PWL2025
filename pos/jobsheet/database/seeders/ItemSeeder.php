<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ItemModel;

class ItemSeeder extends Seeder
{
    public function run()
    {
        ItemModel::create([
            'id' => 1,
            'item_type_id' => 1, // food-beverage
            'name' => 'Kopi Sachet',
            'price' => 2500,
            'stock' => 50,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        ItemModel::create([
            'id' => 2,
            'item_type_id' => 1, // food-beverage
            'name' => 'Teh Celup',
            'price' => 3000,
            'stock' => 40,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        ItemModel::create([
            'id' => 3,
            'item_type_id' => 2, // beauty-health
            'name' => 'Sabun Colek',
            'price' => 5000,
            'stock' => 30,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        ItemModel::create([
            'id' => 4,
            'item_type_id' => 3, // home-care
            'name' => 'Pembersih Lantai',
            'price' => 15000,
            'stock' => 20,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        ItemModel::create([
            'id' => 5,
            'item_type_id' => 4, // baby-kid
            'name' => 'Popok Bayi',
            'price' => 50000,
            'stock' => 15,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}