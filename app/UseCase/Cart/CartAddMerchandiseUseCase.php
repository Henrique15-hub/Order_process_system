<?php
namespace App\UseCase\Cart;

use App\Exceptions\QuantityTooHighException;
use App\Models\Cart;
use App\Service\CartService;
use App\Service\MerchandiseService;

class CartAddMerchandiseUseCase
{
    public function __construct(
        private MerchandiseService $merchandiseService,
        private CartService $cartService,
    ) {
        //
    }


    public function execute(Cart $cart, array $request): array
    {
        $merchandise = $this->merchandiseService->getMerchandise(data_get($request, 'public_id'));

        $item = $this->cartService->findMerchandiseByPublicId($cart, data_get($request, 'public_id'));

        $cartQuantity = data_get($item, 'quantity') ?? 0;


        if ($cartQuantity + data_get($request, 'quantity') > $merchandise->stock_quantity) {
            throw new QuantityTooHighException("Quantity passed to add in cart is more than the system have in stock");
        }

        $preparedMerchandise = $this->cartService->prepareMerchandise($merchandise, data_get($request, 'quantity'));

        if ($item) {
            $this->cartService->addQuantityToCart($cart, $preparedMerchandise);
        }

        if (!$item) {
            $this->cartService->addNewMerchandiseToCart($cart, $preparedMerchandise);
        }

        $this->cartService->sumTheActualValue($cart, data_get($merchandise, "price"), data_get($request, "quantity"));

        return [
            "merchandise" => $merchandise,
            "preparedMerchandise" => $preparedMerchandise
        ];
    }
}
