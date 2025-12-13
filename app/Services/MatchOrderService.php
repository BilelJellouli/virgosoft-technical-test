<?php

namespace App\Services;

use App\Actions\UpdateOrderStatusAction;
use App\Enums\OrderSide;
use App\Enums\OrderStatus;
use App\Events\OrderMatched;
use App\Models\Order;
use App\Models\Asset;
use Illuminate\Support\Facades\DB;

class MatchOrderService
{
    public function __construct(private UpdateOrderStatusAction $updateOrderStatus)
    {
    }

    public function matchBuy(Order $buyOrder)
    {
        $this->matchOrder($buyOrder, OrderSide::SELL, '<=');
    }

    public function matchSell(Order $sellOrder)
    {
        $this->matchOrder($sellOrder, OrderSide::BUY, '>=');
    }

    private function matchOrder(Order $order, OrderSide $counterSide, string $priceOperator)
    {
        $counterOrder = Order::where('symbol', $order->symbol)
            ->where('side', $counterSide)
            ->where('status', OrderStatus::OPEN)
            ->where('price', $priceOperator, $order->price)
            ->orderBy('created_at', 'asc')
            ->first();

        if (is_null($counterOrder)) {
            return;
        }

        DB::transaction(function () use ($order, $counterOrder) {
            $buyer = $order->side === OrderSide::BUY ? $order->user()->lockForUpdate()->first() : $counterOrder->user()->lockForUpdate()->first();
            $seller = $order->side === OrderSide::SELL ? $order->user()->lockForUpdate()->first() : $counterOrder->user()->lockForUpdate()->first();

            $asset = Asset::where('user_id', $seller->id)->where('symbol', $order->symbol)->lockForUpdate()->first();

            $quantity = $order->quantity;
            $price = $counterOrder->price;
            $totalUSD = $quantity * $price;
            $commission = $totalUSD * 0.015;

            $buyer->balance -= $totalUSD;
            $buyer->save();

            $seller->balance += $totalUSD - $commission;
            $seller->save();

            $asset->locked_amount -= $quantity;
            $asset->save();

            $this->updateOrderStatus->execute($order, OrderStatus::FILLED);
            $this->updateOrderStatus->execute($counterOrder, OrderStatus::FILLED);

            OrderMatched::dispatch($order, $counterOrder);
        });
    }
}
