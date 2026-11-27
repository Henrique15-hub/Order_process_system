<?php
namespace App\Repositories;

use App\Enums\CartStatus;
use App\Models\Cart;

class CartRepository
{
    public function store(int $userId): Cart
    {
        return Cart::create([
            "user_id" => $userId,
            "items" => [],
            "actual_value" => 0,
            "status" => CartStatus::Open->value
        ]);

    }

    public function findCartByUserId(int $userId): Cart
    {
        return Cart::where('user_id', $userId)->first();
    }

    public function update(Cart $cart, array $data)
    {
        return $cart->update($data);
    }
}
