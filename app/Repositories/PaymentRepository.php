<?php
namespace App\Repositories;

use App\Enums\PaymentStatusEnum;
use App\Models\Payment;

class PaymentRepository
{
    public function store(
        int $orderId,
        float $value
    ) {
        return Payment::create([
            "order_id" => $orderId,
            "payment_provider" => config('payment.active_provider'),
            "status" => PaymentStatusEnum::Pending,
            "value" => $value
        ]);
    }

    public function update(Payment $payment, array $data)
    {
        return $payment->update($data);
    }

    public function getPayment(int $orderId)
    {
        return Payment::where("order_id", $orderId)->first();
    }
}
