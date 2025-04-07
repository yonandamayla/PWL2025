<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\OrderModel;
use Carbon\Carbon;

class OrderSeeder extends Seeder
{
    public function run(): void
    {
        // Create sample orders
        OrderModel::create([
            'user_id' => 3, // Cashier
            'total_price' => 1200000.00,
            'discount' => 50000.00,
            'status' => 'completed',
            'order_date' => Carbon::now()->subDays(5),
        ]);
        
        OrderModel::create([
            'user_id' => 3, // Cashier
            'total_price' => 545000.00,
            'discount' => 0,
            'status' => 'completed',
            'order_date' => Carbon::now()->subDays(3),
        ]);
        
        OrderModel::create([
            'user_id' => 3, // Cashier
            'total_price' => 9845000.00,
            'discount' => 200000.00,
            'status' => 'processing',
            'order_date' => Carbon::now()->subDay(),
        ]);
    }
}