<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Resources\ProfileResource;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
    public function __invoke(Request $request): JsonResponse
    {
        /** @var User $user */
        $user = $request->user()->load(['orders', 'assets']);

        return response()->json([
            'profile' => ProfileResource::make($user),
        ]);
    }
}
