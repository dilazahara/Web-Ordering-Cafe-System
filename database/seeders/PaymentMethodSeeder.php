<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\PaymentMethod;

class PaymentMethodSeeder extends Seeder
{
    public function run(): void
    {
        // Hindari duplikat kalau dijalankan ulang
        PaymentMethod::truncate();

        PaymentMethod::insert([
            [
                'nama'          => 'Tunai (Cash)',
                'kode'          => 'cash',
                'aktif'         => 1,
                'qris_image'    => null,
                'nama_rekening' => null,
                'no_rekening'   => null,
                'created_at'    => now(),
                'updated_at'    => now(),
            ],
            [
                'nama'          => 'QRIS',
                'kode'          => 'qris',
                'aktif'         => 1,
                'qris_image'    => null,
                'nama_rekening' => null,
                'no_rekening'   => null,
                'created_at'    => now(),
                'updated_at'    => now(),
            ],
            [
                'nama'          => 'Bayar Online (GoPay, OVO, VA Bank, dll.)',
                'kode'          => 'midtrans',
                'aktif'         => 1,
                'qris_image'    => null,
                'nama_rekening' => null,
                'no_rekening'   => null,
                'created_at'    => now(),
                'updated_at'    => now(),
            ],
        ]);
    }
}