<?php
namespace App\Repositories;

use App\Enums\PaymentStatusEnum;
use App\Models\Order;

class OrderRepository
{
    public function store(
        int $userId,
        array $items,
        float $value,
        PaymentStatusEnum $payment_status = PaymentStatusEnum::Pending
    ): Order {

        return Order::create([
            "user_id" => $userId,
            "payment_provider" => config('payment.active_provider'),
            "items" => $items,
            "value" => $value,
            "payment_status" => $payment_status
        ]);
    }

    public function update(Order $order, array $data)
    {
        return $order->update($data);
    }

    public function getOrder(int $orderId)
    {
        return Order::where("id", $orderId)->first();
    }
}
