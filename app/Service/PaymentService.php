<?php
namespace App\Service;

use App\Models\Order;
use App\Models\Payment;
use App\Repositories\PaymentRepository;

class PaymentService
{
    public function __construct(
        private PaymentRepository $paymentRepository
    ) {

    }
    public function store(Order $order)
    {
        return $this->paymentRepository->store(
            orderId: $order->id,
            value: $order->value
        );
    }

    public function update(Payment $payment, array $data)
    {
        return $this->paymentRepository->update($payment, $data);
    }

    public function getPayment(int $orderId)
    {
        return $this->paymentRepository->getPayment($orderId);
    }
}
