<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

#[Fillable(["name", "price", "stock_quantity"])]
class Merchandise extends Model
{
    public function cart(): HasOne
    {
        return $this->hasOne(Cart::class);
    }
}
