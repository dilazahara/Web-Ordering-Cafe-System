<?php

namespace Database\Factories;

use App\Models\Order;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Order>
 */
class OrderFactory extends Factory
{
    protected $model = Order::class;

    public function definition(): array
    {
        return [
            'queue_number'   => strtoupper($this->faker->bothify('Q##')),
            'table_number'   => $this->faker->numberBetween(1, 20),
            'payment_method' => $this->faker->randomElement(['cash', 'qris', 'bca']),
            'status'         => 'pending',
            'total'          => $this->faker->numberBetween(10000, 500000),
            'note'           => null,
            'uang_diterima'  => 0,
            'confirmed_at'   => null,
            'process_at'     => null,
            'done_at'        => null,
        ];
    }

    /** State: order pending */
    public function pending(): static
    {
        return $this->state(['status' => 'pending']);
    }

    /** State: order process */
    public function process(): static
    {
        return $this->state(['status' => 'process']);
    }

    /** State: order done */
    public function done(): static
    {
        return $this->state(['status' => 'done']);
    }

    /** State: order paid */
    public function paid(): static
    {
        return $this->state(['status' => 'paid']);
    }

    /** State: order cancelled (tidak dianggap aktif) */
    public function cancelled(): static
    {
        return $this->state(['status' => 'cancelled']);
    }

    /** State: order selesai */
    public function selesai(): static
    {
        return $this->state(['status' => 'selesai']);
    }
}