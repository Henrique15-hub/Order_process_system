<?php
namespace App\Integrations\Stripe\DTOs;
class PaymentLinkResponse
{
    public function __construct(
        public string $id,
        public string $url,
    ) {
        //
    }

    public function toArray(): array
    {
        return [
            'order_id' => $this->id,
            'url' => $this->url,
        ];
    }
}
