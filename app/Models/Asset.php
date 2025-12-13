<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\AssetSymbols;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
}
