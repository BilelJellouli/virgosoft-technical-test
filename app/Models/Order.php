<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\AssetSymbols;
use App\Enums\OrderSide;
use App\Enums\OrderStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Order extends Model
{
    /** @use HasFactory<\Database\Factories\OrderFactory> */
    use HasFactory;

    protected $fillable = [
        'symbol',
        'side',
        'price',
        'amount',
        'status',
    ];

    protected $casts = [
        'side' => OrderSide::class,
        'symbol' => AssetSymbols::class,
        'status' => OrderStatus::class,
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
