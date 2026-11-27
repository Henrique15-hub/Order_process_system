<?php
namespace App\UseCase\Payment;

use App\Exceptions\QuantityTooHighException;
use App\Models\Order;
use App\Service\MerchandiseService;
use App\Service\PaymentService;

class PaymentCreateUsecase
{
    public function __construct(
        private PaymentService $paymentService,
        private MerchandiseService $merchandiseService,
    ) {

    }

    public function execute(Order $order)
    {
        foreach ($order->items as $item) {
            $merchandise = $this->merchandiseService->getMerchandise(data_get($item, 'public_id'));

            if (data_get($item, 'quantity') > $merchandise->stock_quantity) {
                throw new QuantityTooHighException("The {}");
            }
        }

        return $this->paymentService->store($order);
    }
}
