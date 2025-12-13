<?php

declare(strict_types=1);

namespace App\Actions;

use App\Enums\AssetSymbols;
use App\Enums\OrderSide;
use App\Enums\OrderStatus;
use App\Events\BuyingOrderCreated;
use App\Exceptions\InsufficientBalanceException;
use App\Models\Order;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class CreateBuyingOrderAction
{
    public function execute(
        User $user,
        AssetSymbols $assetSymbols,
        float $price,
        float $quantity,
    ): Order {
        return DB::transaction(function () use ($user, $assetSymbols, $price, $quantity) {
            $total = $price * $quantity;

            if ($user->balance < $total) {
                throw new InsufficientBalanceException;
            }

            $user->lockForUpdate()->update([
                'balance' => $user->balance - $price * $quantity,
            ]);

            $order = $user->orders()->create([
                'side' => OrderSide::BUY,
                'symbol' => $assetSymbols,
                'quantity' => $quantity,
                'price' => $price,
                'status' => OrderStatus::OPEN,
            ]);

            BuyingOrderCreated::dispatch($order);

            return $order;
        });
    }
}
