<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Actions\CancelOrderAction;
use App\Models\Order;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpFoundation\Response as ResponseCode;

class CancelOrdersController extends Controller
{
    public function __invoke(
        CancelOrderAction $cancelOrderAction,
        Request $request,
        Order $order
    ): Response {
        /** @var User $user */
        $user = $request->user();

        if ($user->id !== $order->user_id) {
            abort(ResponseCode::HTTP_FORBIDDEN, 'You are not allowed to cancel this order.');
        }

        try {
            $cancelOrderAction->execute($order);
        } catch (\Exception $exception) {
            throw ValidationException::withMessages([
                'order' => $exception->getMessage(),
            ]);
        }

        return response()->noContent();
    }
}
