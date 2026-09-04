<?php

namespace App\Models;

use App\Enums\PayloadEnum;
use App\Enums\PaymentStatusEnum;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable(['user_id', 'payload', 'value', 'payment_status'])]
class Order extends Model
{
    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class);
    }

    public function cart(): BelongsTo
    {
        return $this->belongsTo(Cart::class);
    }

    public function casts()
    {
        return [
            'payload' => PayloadEnum::class,
            'value' => 'float',
            'payment_status' => PaymentStatusEnum::class,
        ];
    }
}
