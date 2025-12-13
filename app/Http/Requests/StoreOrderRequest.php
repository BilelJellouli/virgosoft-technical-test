<?php

declare(strict_types=1);

namespace App\Http\Requests;

use App\Enums\AssetSymbols;
use App\Enums\OrderSide;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;

class StoreOrderRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'side' => ['required', new Enum(OrderSide::class)],
            'symbol' => ['required', 'string', new Enum(AssetSymbols::class)],
            'price' => ['required', 'numeric', 'min:0.01'],
            'quantity' => ['required', 'numeric', 'min:0.0001'],
            'asset' => ['required_if:side,SELL'],
        ];
    }
}
