<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\OrderItemModel;

class OrderItemSeeder extends Seeder
{
    public function run(): void
    {
        // Order 1 items
        OrderItemModel::create([
            'order_id' => 1,
            'item_id' => 1, // Smartphone X
            'quantity' => 1,
            'subtotal' => 1200000.00,
        ]);
        
        // Order 2 items
        OrderItemModel::create([
            'order_id' => 2,
            'item_id' => 3, // T-shirt Regular Fit
            'quantity' => 2,
            'subtotal' => 300000.00,
        ]);
        
        OrderItemModel::create([
            'order_id' => 2,
            'item_id' => 5, // Premium Coffee Beans 500g
            'quantity' => 1,
            'subtotal' => 95000.00,
        ]);
        
        OrderItemModel::create([
            'order_id' => 2,
            'item_id' => 6, // Notebook Set
            'quantity' => 3,
            'subtotal' => 135000.00,
        ]);
        
        // Order 3 items
        OrderItemModel::create([
            'order_id' => 3,
            'item_id' => 2, // Laptop Pro
            'quantity' => 1,
            'subtotal' => 9500000.00,
        ]);
        
        OrderItemModel::create([
            'order_id' => 3,
            'item_id' => 4, // Denim Jeans
            'quantity' => 1,
            'subtotal' => 350000.00,
        ]);
    }
}