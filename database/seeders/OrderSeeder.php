<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Enums\OrderSide;
use App\Models\Order;
use Illuminate\Database\Seeder;

class OrderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Order::factory()->count(5)->state(['side' => OrderSide::BUY])->create();

        Order::factory()->count(5)->state(['side' => OrderSide::SELL])->create();
    }
}
