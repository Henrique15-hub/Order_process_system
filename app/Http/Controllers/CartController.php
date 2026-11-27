<?php

namespace App\Http\Controllers;

use App\Http\Requests\Cart\CartAddMerchandiseRequest;
use App\Http\Requests\Cart\CartRemoveMerchandiseRequest;
use App\Repositories\CartRepository;
use App\Repositories\MerchandiseRepository;
use App\Service\CartService;
use App\Service\MerchandiseService;
use App\UseCase\Cart\CartAddMerchandiseUseCase;
use App\UseCase\Cart\CartRemoveMerchandiseUseCase;
use Illuminate\Http\JsonResponse;
class CartController extends Controller
{
    public function __construct(
        private CartAddMerchandiseUseCase $cartAddMerchandiseUseCase,
        private CartRemoveMerchandiseUseCase $cartRemoveMerchandiseUseCase,
        private CartRepository $cartRepository,
        private CartService $cartService,
        private MerchandiseService $merchandiseService,
        private MerchandiseRepository $merchandiseRepository,
    ) {

    }

    public function showItems()
    {
        $cartInfo = $this->cartService->showItems(auth()->user()->cart);
        return response()->json([
            'message' => "Showing user's cart items ",
            'data' => $cartInfo,
        ]);
    }


    public function addMerchandiseToCart(CartAddMerchandiseRequest $request): JsonResponse
    {
        $infos = $this->cartAddMerchandiseUseCase->execute(auth()->user()->cart, $request->validated());

        return response()->json([
            'message' => "Merchandise added to user's cart successfully.",
            'data' => [
                'public_id' => data_get($infos, 'merchandise.public_id'),
                'name' => data_get($infos, 'merchandise.name'),
                'price' => data_get($infos, 'merchandise.price'),
                'quantity_added_to_cart' => data_get($infos, 'preparedMerchandise.quantity')
            ],
        ]);
    }

    public function removeMerchandiseToCart(CartRemoveMerchandiseRequest $request): JsonResponse
    {
        $infos = $this->cartRemoveMerchandiseUseCase->execute(auth()->user()->cart, $request->validated());

        return response()->json([
            'message' => "Merchandise subtracted or exclued from user's cart successfully.",
            'data' => [
                'public_id' => data_get($infos, 'merchandise.public_id'),
                'name' => data_get($infos, 'merchandise.name'),
                'price' => data_get($infos, 'merchandise.price'),
                'quantity_remaining_in_cart' => data_get($infos, 'remaining_quantity')
            ],

        ]);
    }
}
