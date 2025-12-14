<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Enums\AssetSymbols;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Asset>
 */
class AssetFactory extends Factory
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
            'amount' => $this->faker->randomFloat(4, 0.1, 5),
            'locked_amount' => 0,
        ];
    }

    public function bitcoin(): self
    {
        return $this->state(['symbol' => AssetSymbols::BTC]);
    }

    public function ethereum(): self
    {
        return $this->state(['symbol' => AssetSymbols::ETH]);
    }
}
