<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ItemTypeModel;

class ItemTypeSeeder extends Seeder
{
    public function run(): void
    {
        $itemTypes = [
            [
                'name' => 'Electronics',
                'description' => 'Electronic devices and accessories'
            ],
            [
                'name' => 'Clothing',
                'description' => 'Apparel and fashion items'
            ],
            [
                'name' => 'Food & Beverage',
                'description' => 'Consumable food and drinks'
            ],
            [
                'name' => 'Stationery',
                'description' => 'Office and school supplies'
            ],
        ];

        foreach ($itemTypes as $type) {
            ItemTypeModel::create($type);
        }
    }
}