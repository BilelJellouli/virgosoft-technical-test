<?php

declare(strict_types=1);

namespace App\Actions;

use App\Enums\OrderStatus;
use App\Events\OrderStatusChanged;
use App\Models\Order;

class UpdateOrderStatusAction
{
    public function execute(Order $order, OrderStatus $status): void
    {
        $order->update(['status' => $status]);

        OrderStatusChanged::dispatch($order);
    }
}
