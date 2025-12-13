<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Enums\AssetSymbols;
use App\Enums\OrderSide;
use App\Enums\OrderStatus;
use App\Http\Resources\OrderResource;
use App\Models\Order;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rules\Enum;

class OrderBooksController extends Controller
{
    public function __invoke(
        Request $request
    ): JsonResponse {
        $request->validate([
            'symbol' => ['required', new Enum(AssetSymbols::class)],
        ]);

        $symbol = $request->symbol;

        $buyOrders = Order::query()
            ->where('symbol', $symbol)
            ->where('side', OrderSide::BUY)
            ->where('status', OrderStatus::OPEN)
            ->orderBy('price', 'desc')
            ->get();

        $sellOrders = Order::query()
            ->where('symbol', $symbol)
            ->where('side', OrderSide::SELL)
            ->where('status', OrderStatus::OPEN)
            ->orderBy('price', 'asc')
            ->get();

        return response()->json([
            'symbol' => $symbol,
            'buy' => OrderResource::collection($buyOrders),
            'sell' => OrderResource::collection($sellOrders),
        ]);
    }
}
