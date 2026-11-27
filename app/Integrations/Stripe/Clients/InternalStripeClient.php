<?php
namespace App\Integrations\Stripe\Clients;

use App\Integrations\Stripe\Contracts\StripeClientInterface;
use App\Integrations\Stripe\DTOs\PaymentLinkPayload;
use App\Integrations\Stripe\DTOs\PaymentLinkResponse;
use Stripe\StripeClient;

class InternalStripeClient implements StripeClientInterface
{
    public function __construct(
        private StripeClient $stripe,
    ) {

    }
    public function createPaymentLink(PaymentLinkPayload $payload, int $orderId): PaymentLinkResponse
    {
        $paymentLink = $this->stripe->checkout->sessions->create($payload->toArray());

        return new PaymentLinkResponse(
            id: $orderId,
            url: $paymentLink->url,
        );
    }
}
