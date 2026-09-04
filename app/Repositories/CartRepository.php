<?php
namespace App\Repositories;

use App\Models\Cart as CartModel;
use App\Entities\Cart as CartEntity;

class CartRepository
{
    public function store(int $userId): CartEntity
    {
        $cartModel = CartModel::create([
            "user_id" => $userId,
            "items" => [],
            "actual_value" => 0
        ]);

        return new CartEntity(
            $cartModel->user_id,
            (array) $cartModel->items,
            $cartModel->actual_value,
        );
    }
}