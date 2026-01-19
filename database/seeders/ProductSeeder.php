<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $products = [
            [
                'sku' => 'OL-SHL-01',
                'name' => 'Oli Shell Helix 4L',
                'description' => 'Oli mesin sintetis berkualitas tinggi untuk menjaga kebersihan mesin dari kerak karbon. Memberikan perlindungan maksimal terhadap keausan dan membuat suara mesin lebih halus.',
                'purchase_price' => 360000,
                'price' => 450000,
                'stock' => 47,
            ],
            [
                'sku' => 'KM-REM-02',
                'name' => 'Kampas Rem Depan',
                'description' => 'Kampas rem original dengan daya cengkram tinggi dan tahan panas. Didesain untuk pengereman yang lebih presisi, tidak berisik, dan tidak merusak piringan cakram (disc brake).',
                'purchase_price' => 210000,
                'price' => 320000,
                'stock' => 49,
            ],
            [
                'sku' => 'FL-UDR-03',
                'name' => 'Filter Udara',
                'description' => 'Filter udara dengan serat fiber khusus untuk menyaring debu dan kotoran secara maksimal. Memastikan sirkulasi udara ke ruang bakar tetap bersih agar tenaga mesin tetap optimal.',
                'purchase_price' => 60000,
                'price' => 100000,
                'stock' => 50,
            ],
            [
                'sku' => 'AK-MFS-06',
                'name' => 'Aki MF Silver (60Ah)',
                'description' => 'Aki kering bebas perawatan dengan daya starter tinggi dan usia pakai lebih lama.',
                'purchase_price' => 780000,
                'price' => 950000,
                'stock' => 100,
            ],
            [
                'sku' => 'BS-IRD-07',
                'name' => 'Busi Iridium Power',
                'description' => 'Busi performa tinggi untuk pembakaran lebih sempurna dan akselerasi lebih ringan.',
                'purchase_price' => 65000,
                'price' => 110000,
                'stock' => 50,
            ],
            [
                'sku' => 'MR-DOT-08',
                'name' => 'Minyak Rem DOT 4',
                'description' => 'Cairan rem tahan panas tinggi untuk mencegah rem blong di kondisi ekstrem.',
                'purchase_price' => 45000,
                'price' => 75000,
                'stock' => 150,
            ],
            [
                'sku' => 'RC-CLT-09',
                'name' => 'Cairan Radiator Coolant',
                'description' => 'Menjaga suhu mesin tetap stabil dan mencegah korosi pada radiator.',
                'purchase_price' => 55000,
                'price' => 90000,
                'stock' => 100,
            ],
            [
                'sku' => 'WP-FLM-10',
                'name' => 'Wiper Blade Frameless',
                'description' => 'Sapuan air maksimal dan sunyi, menjaga visibilitas berkendara saat hujan deras.',
                'purchase_price' => 110000,
                'price' => 185000,
                'stock' => 50,
            ],
            [
                'sku' => 'FO-MAG-11',
                'name' => 'Filter Oli Magnetik',
                'description' => 'Menyaring kotoran dan gram besi halus secara maksimal untuk menjaga mesin.',
                'purchase_price' => 35000,
                'price' => 65000,
                'stock' => 89,
            ],
            [
                'sku' => 'FB-PRM-12',
                'name' => 'Fan Belt Premium',
                'description' => 'Karet fan belt tahan gesekan dan panas tinggi, mencegah suara decit saat mesin menyala.',
                'purchase_price' => 140000,
                'price' => 220000,
                'stock' => 40,
            ],
        ];

        foreach ($products as $product) {
            Product::create($product);
        }
    }
}