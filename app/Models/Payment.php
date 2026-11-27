<?php

namespace App\Models;

use App\Enums\PaymentProviderEnum;
use App\Enums\PaymentStatusEnum;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;

#[Fillable(['order_id', 'payment_provider', 'status', 'value'])]
class Payment extends Model
{
    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function casts()
    {
        return [
            'payment_provider' => PaymentProviderEnum::class,
            'status' => PaymentStatusEnum::class
        ];
    }
}
