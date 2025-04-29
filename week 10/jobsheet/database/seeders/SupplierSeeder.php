<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SupplierSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('m_supplier')->insert([
            [
                'supplier_id' => 1,
                'supplier_kode' => 'SUP001',
                'supplier_nama' => 'PT. Elektronik Jaya',
                'supplier_alamat' => 'Jl. Industri No. 123, Jakarta',
                'supplier_telp' => '021-5551234',
                'supplier_email' => 'info@elektronikjaya.com',
                'supplier_kontak' => 'Budi Santoso',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'supplier_id' => 2,
                'supplier_kode' => 'SUP002',
                'supplier_nama' => 'CV. Fashion Indonesia',
                'supplier_alamat' => 'Jl. Tekstil No. 45, Bandung',
                'supplier_telp' => '022-6667890',
                'supplier_email' => 'sales@fashionindonesia.co.id',
                'supplier_kontak' => 'Dewi Lestari',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'supplier_id' => 3,
                'supplier_kode' => 'SUP003',
                'supplier_nama' => 'UD. Makanan Sehat',
                'supplier_alamat' => 'Jl. Pasar Baru No. 78, Surabaya',
                'supplier_telp' => '031-7772345',
                'supplier_email' => 'order@makanansehat.com',
                'supplier_kontak' => 'Agus Wijaya',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'supplier_id' => 4,
                'supplier_kode' => 'SUP004',
                'supplier_nama' => 'PT. Minuman Prima',
                'supplier_alamat' => 'Jl. Industri Minuman Blok A5, Tangerang',
                'supplier_telp' => '021-8883456',
                'supplier_email' => 'cs@minumanprima.com',
                'supplier_kontak' => 'Siti Rahayu',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'supplier_id' => 5,
                'supplier_kode' => 'SUP005',
                'supplier_nama' => 'PT. Farma Indonesia',
                'supplier_alamat' => 'Jl. Farmasi No. 56, Semarang',
                'supplier_telp' => '024-9994567',
                'supplier_email' => 'info@farmaindonesia.com',
                'supplier_kontak' => 'Dr. Hendra Wijaya',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}