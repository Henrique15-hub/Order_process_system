<?php

namespace App\Models;

use App\Enums\CartStatus;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable(['user_id', 'items', 'status', 'actual_value'])]
class Cart extends Model
{
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function merchandise(): HasMany
    {
        return $this->hasMany(Merchandise::class);
    }

    public function order(): HasMany
    {
        return $this->hasMany(Order::class);
    }

    public function casts()
    {
        return [
            'items' => 'array',
            'actual_value' => 'float',
            'status' => CartStatus::class
        ];
    }

    public function getItems()
    {
        return $this->items;
    }

    public function getActualValue()
    {
        return $this->actual_value;
    }

}