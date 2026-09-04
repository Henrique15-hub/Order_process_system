<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

#[Fillable(["business_account_id", "name", "price", "stock_quantity", "user_id"])]
class Merchandise extends Model
{
    public function cart(): HasOne
    {
        return $this->hasOne(Cart::class);
    }

    public function casts()
    {
        return [
            'name' => 'string',
            'price' => 'float',
            'stock_quantity' => 'integer',
        ];
    }
}
