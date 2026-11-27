<?php
namespace App\UseCase\Order;

use App\Exceptions\Cart\CartEmptyException;
use App\Models\User;
use App\Service\CartService;
use App\Service\MerchandiseService;
use App\UseCase\Payment\PaymentCreateUsecase;
use App\UseCase\Payment\PaymentLinkCreateUsecase;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;

class OrderBuildUseCase
{
    public function __construct(
        private CartService $cartService,
        private PaymentLinkCreateUsecase $paymentLinkCreateUsecase,
        private OrderCreateUseCase $orderCreateUseCase,
        private PaymentCreateUsecase $paymentCreateUseCase,
        private MerchandiseService $merchandiseService
    ) {

    }
    public function execute(User $user): JsonResponse
    {
        if ($user->cart->items === []) {
            throw new CartEmptyException();
        }

        return DB::transaction(function () use ($user) {
            $this->cartService->closeCart($user->cart);

            $order = $this->orderCreateUseCase->execute($user->cart);

            $this->paymentCreateUseCase->execute($order);

            $paymentLink = $this->paymentLinkCreateUsecase->execute(
                cart: $user->cart,
                order: $order
            );

            return response()->json($paymentLink->toArray());
        });
    }
}
