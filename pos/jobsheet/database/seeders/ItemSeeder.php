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
            'name' => 'Kopi Kapal Api',
            'description' => 'Kopi bubuk sachet premium dengan rasa kuat dan aroma yang khas. Cocok untuk dinikmati pagi dan sore hari.',
            'price' => 2500,
            'stock' => 50,
            'photo' => 'storage/items/kopi-sachet.jpg',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        ItemModel::create([
            'id' => 2,
            'item_type_id' => 1, // food-beverage
            'name' => 'Teh Celup Sariwangi',
            'description' => 'Teh celup berkualitas tinggi dengan daun teh pilihan. Menghasilkan seduhan yang harum dan menyegarkan.',
            'price' => 3000,
            'stock' => 40,
            'photo' => 'storage/items/teh-celup.jpg',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        ItemModel::create([
            'id' => 3,
            'item_type_id' => 2, // beauty-health
            'name' => 'Sabun Colek Wing',
            'description' => 'Sabun colek untuk mencuci pakaian. Efektif menghilangkan noda membandel dan berbau harum.',
            'price' => 5000,
            'stock' => 30,
            'photo' => 'storage/items/sabun-colek.jpg',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        ItemModel::create([
            'id' => 4,
            'item_type_id' => 3, // home-care
            'name' => 'Soklin Pembersih Lantai',
            'description' => 'Cairan pembersih lantai dengan formula anti bakteri. Membuat lantai bersih mengkilap dan wangi tahan lama.',
            'price' => 15000,
            'stock' => 20,
            'photo' => 'storage/items/pembersih-lantai.jpg',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        ItemModel::create([
            'id' => 5,
            'item_type_id' => 4, // baby-kid
            'name' => 'Popok Bayi Mamipoko',
            'description' => 'Popok bayi sekali pakai dengan daya serap tinggi. Lembut di kulit dan tidak menyebabkan iritasi.',
            'price' => 50000,
            'stock' => 15,
            'photo' => 'storage/items/popok-bayi.jpg',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        ItemModel::create([
            'id' => 6,
            'item_type_id' => 1, // food-beverage
            'name' => 'Mie Instan',
            'description' => 'Mie instan dengan bumbu yang kaya rasa. Praktis disiapkan hanya dalam 3 menit. Tersedia dalam berbagai varian rasa.',
            'price' => 3500,
            'stock' => 100,
            'photo' => 'storage/items/mie-instan.jpg',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        
        ItemModel::create([
            'id' => 7,
            'item_type_id' => 2, // beauty-health
            'name' => 'Sampo Anti Ketombe',
            'description' => 'Sampo dengan formula khusus untuk mengatasi masalah ketombe. Membuat rambut bersih, segar dan bebas ketombe sepanjang hari.',
            'price' => 20000,
            'stock' => 35,
            'photo' => 'storage/items/sampo-ketombe.jpg',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        
        ItemModel::create([
            'id' => 8,
            'item_type_id' => 3, // home-care
            'name' => 'Pewangi Pakaian',
            'description' => 'Pewangi pakaian konsentrat dengan wangi bunga yang tahan lama. Membuat pakaian wangi segar hingga 7 hari.',
            'price' => 18000,
            'stock' => 25,
            'photo' => 'storage/items/pewangi-pakaian.jpg',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        
        ItemModel::create([
            'id' => 9,
            'item_type_id' => 4, // baby-kid
            'name' => 'Bubur Bayi',
            'description' => 'Bubur bayi instan dengan nutrisi lengkap untuk tumbuh kembang bayi. Mudah dicerna dan tersedia berbagai rasa buah.',
            'price' => 12000,
            'stock' => 30,
            'photo' => 'storage/items/bubur-bayi.jpg',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        
        ItemModel::create([
            'id' => 10,
            'item_type_id' => 1, // food-beverage
            'name' => 'Biskuit Sandwich',
            'description' => 'Biskuit sandwich lezat dengan krim di tengahnya. Renyah di luar, lembut di dalam. Cocok untuk camilan keluarga.',
            'price' => 8500,
            'stock' => 45,
            'photo' => 'storage/items/biskuit-sandwich.jpg',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}