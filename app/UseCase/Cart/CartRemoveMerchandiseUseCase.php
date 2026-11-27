<?php
namespace App\UseCase\Cart;

use App\Exceptions\Merchandise\NotFoundedMerchandiseException;
use App\Exceptions\QuantityTooHighException;
use App\Models\Cart;
use App\Service\CartService;

class CartRemoveMerchandiseUseCase
{
    public function __construct(
        private CartService $cartService
    ) {
        //
    }

    public function execute(Cart $cart, array $request): array
    {

        $merchandise = $this->cartService->findMerchandiseByPublicId($cart, data_get($request, "public_id"));

        if (!$merchandise) {
            throw new NotFoundedMerchandiseException("Merchandise not found in user cart");
        }

        if (data_get($merchandise, "quantity") < data_get($request, "quantity")) {
            throw new QuantityTooHighException("quantity in cart is less than quantity in request");
        }

        $remainingQuantity = data_get($merchandise, "quantity") - data_get($request, "quantity");


        if ($remainingQuantity === 0) {
            $this->cartService->removeMerchandise($cart, data_get($request, "public_id"));
        }

        if ($remainingQuantity > 0) {
            $this->cartService->decreaseQuantityToCart($cart, $request);
        }

        $this->cartService->subTheActualValue($cart, data_get($merchandise, "price"), data_get($request, "quantity"));

        return [
            "merchandise" => $merchandise,
            "remaining_quantity" => $remainingQuantity
        ];
    }


}
