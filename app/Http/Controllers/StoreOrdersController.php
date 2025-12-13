<?php

namespace App\Http\Controllers;

use App\Actions\CreateBuyingOrderAction;
use App\Actions\CreateSellingOrderAction;
use App\Enums\OrderSide;
use App\Http\Requests\StoreOrderRequest;
use App\Http\Resources\OrderResource;
use App\Models\Asset;
use App\Models\User;
use App\Services\MatchOrderService;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class StoreOrdersController extends Controller
{
    public function __invoke(
        CreateBuyingOrderAction $createBuyingOrder,
        CreateSellingOrderAction $createSellingOrder,
        MatchOrderService $matchOrderService,
        StoreOrderRequest $request,
    ): JsonResponse {
        /** @var User $user */
        $user = $request->user();
        $side = $request->input('side');
        $symbol = $request->input('symbol');
        $price = $request->input('price');
        $quantity = $request->input('quantity');

        try {
            if ($side === OrderSide::BUY) {
                $order = $createBuyingOrder->execute($user, $symbol, $price, $quantity);
                $matchOrderService->matchBuy($order);
            } else {
                $asset = Asset::where('symbol', $symbol)->where('user_id', $user->id)->findOrFail();
                $order = $createSellingOrder->execute($user, $asset, $quantity, $price);
                $matchOrderService->matchSell($order);
            }
        } catch (\Exception $exception) {
            return response()->json([
                'message' => $exception->getMessage(),
            ], Response::HTTP_BAD_REQUEST);
        }

        return response()->json([
            'order' => OrderResource::make($order),
        ], Response::HTTP_CREATED);
    }
}
