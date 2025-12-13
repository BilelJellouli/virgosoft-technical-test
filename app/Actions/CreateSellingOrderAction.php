<?php

namespace App\Actions;

use App\Enums\OrderSide;
use App\Enums\OrderStatus;
use App\Events\SellingOrderCreated;
use App\Models\Asset;
use App\Models\Order;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class CreateSellingOrderAction
{
    public function execute(
        User $user,
        Asset $asset,
        float $quantity,
        float $price,
    ): Order {
        return DB::transaction(function () use ($user, $asset, $quantity, $price) {
            $asset->lockForUpdate()->update([
                'amount' => $asset->amount - $quantity,
                'locked_amount' => $asset->locked_amount + $quantity,
            ]);

            $order = $user->orders()->create([
                'side' => OrderSide::SELL,
                'symbol' => $asset->symbol,
                'quantity' => $quantity,
                'price' => $price,
                'status' => OrderStatus::OPEN,
            ]);

            SellingOrderCreated::dispatch($order);

            return $order;
        });
    }
}
