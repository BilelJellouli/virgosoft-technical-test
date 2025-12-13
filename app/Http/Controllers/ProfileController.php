<?php

namespace App\Http\Controllers;

use App\Http\Resources\ProfileResource;
use App\Models\User;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
    public function __invoke(Request $request)
    {
        /** @var User $user */
        $user = $request->user()->load('orders', 'assets');

        return [
            'profile' => ProfileResource::make($user),
        ];
    }
}
