<?php

namespace Database\Seeders;

use App\Models\Service;
use Illuminate\Database\Seeder;

class LayananSeeder extends Seeder
{
    public function run(): void
    {
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
                'description' => 'Kembalikan tarikan mesin yang berat menjadi ringan. Paket ini mengganti filter udara yang kotor dan busi lama dengan tipe Iridium untuk pembakaran yang jauh lebih sempurna dan responsif.',
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
                'description' => 'Perawatan performa mesin meliputi pembersihan Throttle Body (TB), pengecekan kondisi busi, pembersihan filter udara, serta reset sistem sensor (jika diperlukan).',
                'price' => 200000,
                'discount_price' => null,
            ],
            [
                'name' => 'Jasa Ganti Aki & Cek Alternator',
                'description' => 'Jasa pemasangan aki baru disertai pengecekan sistem pengisian (alternator) untuk memastikan aki baru tidak cepat tekor/rusak.',
                'price' => 50000,
                'discount_price' => 35000,
            ],
            [
                'name' => 'Jasa Fogging Sterilisasi',
                'description' => 'Proses sterilisasi kabin menggunakan mesin fogging untuk membunuh kuman, bakteri, dan jamur di saluran AC serta interior mobil agar udara kembali sehat.',
                'price' => 100000,
                'discount_price' => 80000,
            ],
        ];

        foreach ($services as $service) {
            Service::create($service);
        }
    }
}