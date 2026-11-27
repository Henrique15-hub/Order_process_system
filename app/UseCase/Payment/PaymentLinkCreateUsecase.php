<?php
namespace App\UseCase\Payment;

use App\Integrations\Stripe\Contracts\StripeClientInterface;
use App\Integrations\Stripe\DTOs\PaymentLinkPayload;
use App\Integrations\Stripe\DTOs\PaymentLinkResponse;
use App\Models\Cart;
use App\Models\Order;

class PaymentLinkCreateUsecase
{
    public function __construct(
        private StripeClientInterface $internalStripeClient,
    ) {

    }

    public function execute(Cart $cart, Order $order): PaymentLinkResponse
    {
        $payload = new PaymentLinkPayload(
            items: data_get($cart, "items"),
            metadata: [
                'order_id' => $order->id,
                'user_id' => $order->user_id,
            ],
        );

        return $this->internalStripeClient->createPaymentLink($payload, $order->id);
    }
}