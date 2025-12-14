<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\AssetSymbols;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Asset extends Model
{
    /** @use HasFactory<\Database\Factories\AssetFactory> */
    use HasFactory;

    protected $fillable = [
        'user_id',
        'symbol',
        'amount',
    ];

    protected $casts = [
        'symbol' => AssetSymbols::class,
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
