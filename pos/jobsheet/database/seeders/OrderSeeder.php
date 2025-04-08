<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\OrderModel;

class OrderSeeder extends Seeder
{
    public function run()
    {
        OrderModel::create([
            'id' => 1,
            'user_id' => 1, // Admin User
            'total_price' => 25000,
            'discount' => 10, // 10% diskon
            'status' => 'completed',
            'order_date' => now()->subDays(2),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        OrderModel::create([
            'id' => 2,
            'user_id' => 2, // Kasir User
            'total_price' => 15000,
            'discount' => 0,
            'status' => 'pending',
            'order_date' => now()->subDay(),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        OrderModel::create([
            'id' => 3,
            'user_id' => 3, // Kasir Dua
            'total_price' => 50000,
            'discount' => 5, // 5% diskon
            'status' => 'completed',
            'order_date' => now(),
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}