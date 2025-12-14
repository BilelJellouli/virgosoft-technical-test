<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Enums\AssetSymbols;
use App\Enums\OrderSide;
use App\Enums\OrderStatus;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Order>
 */
class OrderFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'symbol' => $this->faker->randomElement(AssetSymbols::cases()),
            'side' => $this->faker->randomElement(OrderSide::cases()),
            'price' => $this->faker->randomFloat(2, 100, 100000),
            'amount' => $this->faker->randomFloat(4, 0.01, 10),
            'status' => OrderStatus::OPEN,
        ];
    }
}
