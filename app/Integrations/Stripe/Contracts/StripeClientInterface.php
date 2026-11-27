<?php
namespace App\Integrations\Stripe\Contracts;

use App\Integrations\Stripe\DTOs\PaymentLinkPayload;
use App\Integrations\Stripe\DTOs\PaymentLinkResponse;
interface StripeClientInterface
{
    public function createPaymentLink(PaymentLinkPayload $payload, int $orderId): PaymentLinkResponse;
}