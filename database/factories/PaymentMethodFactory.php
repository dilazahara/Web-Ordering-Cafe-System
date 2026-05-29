<?php

namespace Database\Factories;

use App\Models\PaymentMethod;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<PaymentMethod>
 */
class PaymentMethodFactory extends Factory
{
    protected $model = PaymentMethod::class;

    public function definition(): array
    {
        $kode = $this->faker->unique()->randomElement([
            'cash', 'qris', 'bca', 'bni', 'bri', 'mandiri', 'gopay', 'ovo', 'dana',
        ]);

        return [
            'nama'          => 'Metode ' . strtoupper($kode),
            'kode'          => $kode,
            'aktif'         => true,
            'qris_image'    => null,
            'nama_rekening' => null,
            'no_rekening'   => null,
        ];
    }

    /** State: metode non-aktif */
    public function nonaktif(): static
    {
        return $this->state(['aktif' => false]);
    }

    /** State: metode QRIS dengan merchant info */
    public function qris(): static
    {
        return $this->state([
            'kode'          => 'qris',
            'nama'          => 'QRIS',
            'qris_image'    => null,
            'nama_rekening' => null,
            'no_rekening'   => null,
        ]);
    }
}