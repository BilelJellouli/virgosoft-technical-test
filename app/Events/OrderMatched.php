<?php

declare(strict_types=1);

namespace App\Events;

use App\Models\Order;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class OrderMatched implements ShouldBroadcast
{
    use Dispatchable;
    use InteractsWithSockets;
    use SerializesModels;

    public function __construct(
        public readonly Order $order,
        public readonly Order $counterOrder
    ) {}

    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('user.'.$this->order->user_id),
            new PrivateChannel('user.'.$this->counterOrder->user_id),
        ];
    }
}
