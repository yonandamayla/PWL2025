<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ItemModel;

class ItemSeeder extends Seeder
{
    public function run(): void
    {
        // Electronics (Type ID 1)
        ItemModel::create([
            'item_type_id' => 1,
            'name' => 'Smartphone X',
            'price' => 1200000.00,
            'stock' => 50,
        ]);
        
        ItemModel::create([
            'item_type_id' => 1,
            'name' => 'Laptop Pro',
            'price' => 9500000.00,
            'stock' => 30,
        ]);
        
        // Clothing (Type ID 2)
        ItemModel::create([
            'item_type_id' => 2,
            'name' => 'T-shirt Regular Fit',
            'price' => 150000.00,
            'stock' => 100,
        ]);
        
        ItemModel::create([
            'item_type_id' => 2,
            'name' => 'Denim Jeans',
            'price' => 350000.00,
            'stock' => 75,
        ]);
        
        // Food & Beverage (Type ID 3)
        ItemModel::create([
            'item_type_id' => 3,
            'name' => 'Premium Coffee Beans 500g',
            'price' => 95000.00,
            'stock' => 80,
        ]);
        
        // Stationery (Type ID 4)
        ItemModel::create([
            'item_type_id' => 4,
            'name' => 'Notebook Set',
            'price' => 45000.00,
            'stock' => 200,
        ]);
    }
}