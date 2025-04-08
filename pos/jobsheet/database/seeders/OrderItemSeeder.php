<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\OrderItemModel;

class OrderItemSeeder extends Seeder
{
    public function run()
    {
        OrderItemModel::create([
            'id' => 1,
            'order_id' => 1,
            'item_id' => 1, // Kopi Sachet
            'quantity' => 5,
            'subtotal' => 12500, // 5 x 2500
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        OrderItemModel::create([
            'id' => 2,
            'order_id' => 1,
            'item_id' => 2, // Teh Celup
            'quantity' => 5,
            'subtotal' => 15000, // 5 x 3000
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        OrderItemModel::create([
            'id' => 3,
            'order_id' => 2,
            'item_id' => 3, // Sabun Colek
            'quantity' => 3,
            'subtotal' => 15000, // 3 x 5000
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        OrderItemModel::create([
            'id' => 4,
            'order_id' => 3,
            'item_id' => 5, // Popok Bayi
            'quantity' => 1,
            'subtotal' => 50000, // 1 x 50000
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}