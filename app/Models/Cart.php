<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Casts\AsArrayObject;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable(['user_id', 'items', 'actual_value'])]
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
            'items' => AsArrayObject::class,
            'actual_value' => 'float',
        ];
    }



}
