<?php

namespace Database\Seeders;

use App\Models\Service;
use App\Models\Product;
use Illuminate\Database\Seeder;

class LayananSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Definisikan Data Layanan
        $services = [
            [
                'name' => 'Paket Ganti Oli Silver',
                'description' => 'Paket perawatan oli standar untuk menjaga kebersihan mesin. Sudah termasuk filter oli magnetik untuk menyaring gram besi halus.',
                'price' => 565000,
                'discount_price' => 495000,
            ],
            [
                'name' => 'Paket Pengereman Pakem',
                'description' => 'Solusi pengereman pakem dan aman. Mengganti kampas rem lama dengan stok baru dan pengurasan minyak rem kualitas DOT 4.',
                'price' => 480000,
                'discount_price' => 415000,
            ],
            [
                'name' => 'Paket Refresh Performa (Tune Up)',
                'description' => 'Kembalikan tarikan mesin yang berat menjadi ringan. Paket ini mengganti filter udara yang kotor dan busi lama dengan tipe Iridium.',
                'price' => 410000,
                'discount_price' => 355000,
            ],
            [
                'name' => 'Jasa Ganti Oli Mesin',
                'description' => 'Layanan kuras oli mesin lama, pembersihan area baut pembuangan, pemasangan filter oli baru, dan pengecekan kebocoran paking mesin.',
                'price' => 60000,
                'discount_price' => null,
            ],
            [
                'name' => 'Service Rem (Cleaning)',
                'description' => 'Pembersihan total debu asbes pada sistem pengereman, pemberian pelumas (grease) pada pin kaliper agar tidak macet, dan penyetelan ulang jarak pengereman.',
                'price' => 100000,
                'discount_price' => null,
            ],
            [
                'name' => 'Jasa Tune Up Ringan',
                'description' => 'Perawatan performa mesin meliputi pembersihan Throttle Body (TB), pengecekan kondisi busi, pembersihan filter udara, serta reset sistem sensor.',
                'price' => 200000,
                'discount_price' => null,
            ],
            [
                'name' => 'Jasa Ganti Aki & Cek Alternator',
                'description' => 'Jasa pemasangan aki baru disertai pengecekan sistem pengisian (alternator).',
                'price' => 50000,
                'discount_price' => 35000,
            ],
            [
                'name' => 'Jasa Fogging Sterilisasi',
                'description' => 'Proses sterilisasi kabin menggunakan mesin fogging untuk membunuh kuman, bakteri, dan jamur.',
                'price' => 100000,
                'discount_price' => 80000,
            ],
        ];

        // 2. Loop dan Hubungkan ke Produk
        foreach ($services as $data) {
            $service = Service::create($data);

            // Mapping Bundling berdasarkan Nama Layanan
            switch ($service->name) {
                case 'Paket Ganti Oli Silver':
                    $oli = Product::where('sku', 'OL-SHL-01')->first();
                    $filterOli = Product::where('sku', 'FO-MAG-11')->first();
                    if ($oli) $service->products()->attach($oli->id, ['quantity' => 1]);
                    if ($filterOli) $service->products()->attach($filterOli->id, ['quantity' => 1]);
                    break;

                case 'Paket Pengereman Pakem':
                    $kampas = Product::where('sku', 'KM-REM-02')->first();
                    $minyakRem = Product::where('sku', 'MR-DOT-08')->first();
                    if ($kampas) $service->products()->attach($kampas->id, ['quantity' => 1]);
                    if ($minyakRem) $service->products()->attach($minyakRem->id, ['quantity' => 1]);
                    break;

                case 'Paket Refresh Performa (Tune Up)':
                    $filterUdara = Product::where('sku', 'FL-UDR-03')->first();
                    $busi = Product::where('sku', 'BS-IRD-07')->first();
                    if ($filterUdara) $service->products()->attach($filterUdara->id, ['quantity' => 1]);
                    if ($busi) $service->products()->attach($busi->id, ['quantity' => 1]);
                    break;
            }
        }
    }
}