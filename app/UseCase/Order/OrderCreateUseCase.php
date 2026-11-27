<?php
namespace App\UseCase\Order;

use App\Models\Cart;
use App\Models\User;
use App\Service\OrderService;

class OrderCreateUseCase
{
    public function __construct(
        private OrderService $orderService
    ) {
        //
    }

    public function execute(Cart $cart)
    {
        return $this->orderService->store(
            $cart->user->id,
            $cart->items,
            $cart->actual_value
        );

    }
}