<?php
namespace App\UseCase\Payment;

use App\Enums\PaymentStatusEnum;
use App\Service\CartService;
use App\Service\OrderService;
use App\Service\PaymentService;
use Illuminate\Support\Facades\DB;

class PaymentExpiredUseCase
{
    public function __construct(
        private CartService $cartService,
        private PaymentService $paymentService,
        private OrderService $orderService,
    ) {

    }

    public function execute($data)
    {
        DB::transaction(function () use ($data) {
            $cart = $this->cartService->getCart(data_get($data, 'metadata.user_id'));

            $this->cartService->openCart($cart);

            $payment = $this->paymentService->getPayment(data_get($data, 'metadata.order_id'));

            $this->paymentService->update($payment, ['status' => PaymentStatusEnum::UnPaid]);

            $order = $this->orderService->getOrder(data_get($data, 'metadata.order_id'));

            $this->orderService->update($order, ['status' => PaymentStatusEnum::UnPaid]);
        });

    }
}