<?php

namespace App\Actions;

use App\Models\Order;

class CancelOrderAction
{
    public function __invoke(Order $order): void
    {
        if ($order->status !== 'OPEN') {
            return;
        }

        if ($order->type === 'BUY') {
            // Release locked USD
            $order->user->increment('usd_balance', $order->locked_usd);
            $order->locked_usd = 0;
        } elseif ($order->type === 'SELL') {
            // Release locked assets
            $order->user->increment('asset_balance', $order->locked_assets);
            $order->locked_assets = 0;
        }

        $order->status = 'CANCELLED';
        $order->save();
    }
}
