<?php
namespace App\Integrations\Stripe\DTOs;

class PaymentLinkPayload
{
    public function __construct(
        private array $items,
        private array $metadata,

    ) { //
    }

    public function toArray(): array
    {

        $lineItems = [];

        foreach ($this->items as $item) {
            $lineItems[] = [
                'price_data' => [
                    'currency' => 'brl',
                    'product_data' => [
                        'name' => data_get($item, "name"),
                    ],
                    'unit_amount' => data_get($item, "price"),
                ],
                'quantity' => data_get($item, "quantity")
            ];
        }


        return [
            'mode' => 'payment',

            'line_items' => $lineItems,
            'success_url' => config('services.stripe.sucess_url'),
            'cancel_url' => config('services.stripe.cancel_url'),

            'metadata' => $this->metadata,
        ];
    }

}
