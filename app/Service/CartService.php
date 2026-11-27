<?php
namespace App\Service;

use App\Enums\CartStatus;
use App\Models\Cart;
use App\Models\Merchandise;
use App\Repositories\CartRepository;

class CartService
{
    public function __construct(
        private CartRepository $cartRepository
    ) {

    }

    public function getCart(int $userId)
    {
        return $this->cartRepository->findCartByUserId($userId);
    }

    public function store(int $userId)
    {
        return $this->cartRepository->store($userId);
    }
    public function findMerchandiseByPublicId(Cart $cart, string $publicId): array|false
    {
        foreach ($cart->items as $item) {
            if (data_get($item, "public_id") !== $publicId) {
                continue;
            }
            return $item;
        }

        return false;
    }

    public function prepareMerchandise(Merchandise $merchandise, int $requestQuantity): array
    {
        $preparedMerchandise = $merchandise->toArray();
        unset($preparedMerchandise["stock_quantity"]);

        $preparedMerchandise["quantity"] = $requestQuantity;

        return $preparedMerchandise;
    }

    public function addNewMerchandiseToCart(Cart $cart, array $preparedMerchandise)
    {
        $items = $cart->items ?? [];

        $items[] = $preparedMerchandise;

        return $this->cartRepository->update($cart, [
            'items' => $items,
        ]);
    }

    public function addQuantityToCart(Cart $cart, array $preparedMerchandise): bool
    {
        $items = $cart->items;

        foreach ($items as $key => $item) {
            if (
                data_get($item, 'public_id') !==
                data_get($preparedMerchandise, 'public_id')
            ) {
                continue;
            }

            $quantity = data_get($item, 'quantity');
            $quantity += data_get($preparedMerchandise, 'quantity');

            $items[$key]['quantity'] = $quantity;


            return $this->cartRepository->update($cart, [
                'items' => $items,
            ]);
        }

        return false;
    }
    public function sumTheActualValue(Cart $cart, float $itemPrice, int $quantity)
    {
        return $this->cartRepository->update($cart, [
            'actual_value' => $cart->actual_value += ($itemPrice * $quantity),
        ]);
    }

    public function subTheActualValue(Cart $cart, float $itemPrice, int $quantity)
    {
        return $this->cartRepository->update($cart, [
            'actual_value' => $cart->actual_value -= ($itemPrice * $quantity),
        ]);
    }

    public function showItems(Cart $cart)
    {
        $build = [];
        foreach ($cart->items as $item) {
            unset($item['id']);
            $build[] = $item;
        }

        return $build;
    }

    public function removeMerchandise(Cart $cart, string $publicId): bool
    {

        $items = $cart->items;

        foreach ($cart->items as $key => $item) {
            if (data_get($item, "public_id") !== $publicId) {
                continue;
            }

            unset($items[$key]);
            return $this->cartRepository->update($cart, [
                'items' => $items,
            ]);
        }

        return false;

    }

    public function decreaseQuantityToCart(Cart $cart, array $merchandiseInfo): bool
    {
        $items = $cart->items;

        foreach ($cart->items as $key => $item) {

            if (data_get($item, "public_id") !== data_get($merchandiseInfo, "public_id")) {
                continue;
            }

            $quantity = data_get($item, 'quantity');
            $quantity -= data_get($merchandiseInfo, 'quantity');

            $items[$key]['quantity'] = $quantity;

            return $this->cartRepository->update($cart, [
                'items' => $items,
            ]);
        }

        return false;

    }

    public function closeCart(Cart $cart)
    {
        $cart->status = CartStatus::Closed;

        return $cart->save();
    }

    public function openCart(Cart $cart)
    {
        return $this->cartRepository->update($cart, [
            'status' => CartStatus::Open,
        ]);
    }

    public function clearCart(Cart $cart)
    {
        return $this->cartRepository->update($cart, [
            'items' => [],
        ]);
    }

}