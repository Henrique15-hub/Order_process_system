<?php
namespace App\UseCase\Payment;

use App\Enums\PaymentStatusEnum;
use App\Service\CartService;
use App\Service\MerchandiseService;
use App\Service\OrderService;
use App\Service\PaymentService;
use Illuminate\Support\Facades\DB;



class PaymentCompletedUseCase
{
    public function __construct(
        private CartService $cartService,
        private PaymentService $paymentService,
        private OrderService $orderService,
        private MerchandiseService $merchandiseService,
    ) {

    }
    public function execute($data)
    {
        DB::transaction(function () use ($data) {

            $cart = $this->cartService->getCart(data_get($data, 'metadata.user_id'));

            $this->cartService->clearCart($cart);

            $this->cartService->openCart($cart);

            $payment = $this->paymentService->getPayment(data_get($data, 'metadata.order_id'));

            $this->paymentService->update($payment, [
                'status' => PaymentStatusEnum::Paid
            ]);

            $order = $this->orderService->getOrder(data_get($data, 'metadata.order_id'));

            $this->orderService->update($order, [
                'payment_status' => PaymentStatusEnum::Paid
            ]);

            $this->merchandiseService->subtractFromStock($order->items);
        });

    }
}