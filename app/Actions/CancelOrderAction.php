<?php

namespace App\Actions;

use App\Enums\OrderSide;
use App\Enums\OrderStatus;
use App\Events\OrderCancelled;
use App\Models\Order;

class CancelOrderAction
{
    public function __construct(protected UpdateOrderStatusAction $updateOrderStatus)
    {
    }

    public function execute(Order $order): void
    {
        if ($order->status !== OrderStatus::OPEN) {
            throw new \LogicException('Only Open orders can be cancelled.');
        }

        match (true) {
            $order->side === OrderSide::BUY => $this->cancelBuyingOrder($order),
            $order->side === OrderSide::SELL => $this->cancelSellingOrder($order),
            default => null,
        };

        $this->updateOrderStatus->execute($order, OrderStatus::CLOSED);
    }

    private function cancelSellingOrder(Order $order): void
    {
        $asset = $order->user->assets()->where('symbol', $order->symbol)->first();

        if (is_null($asset)) {
            throw new \LogicException('Asset not found.');
        }

        $asset->amount += $order->quantity;
        $asset->save();

        OrderCancelled::dispatch($order);
    }

    private function cancelBuyingOrder(Order $order): void
    {
        $refund = $order->price * $order->quantity;
        $order->user->increment('balance', $refund);
    }
}
