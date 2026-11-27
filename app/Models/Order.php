<?php

namespace App\Models;

use App\Enums\PaymentProviderEnum;
use App\Enums\PaymentStatusEnum;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable(['user_id', 'payment_provider', 'items', 'value', 'payment_status'])]
class Order extends Model
{
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function cart(): BelongsTo
    {
        return $this->belongsTo(Cart::class);
    }

    public function casts()
    {
        return [
            'value' => 'float',
            'payment_status' => PaymentStatusEnum::class,
            'items' => 'array',
            'payment_provider' => PaymentProviderEnum::class,
        ];
    }
}
