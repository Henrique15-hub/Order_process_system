<?php
namespace App\Service;

use App\Models\Order;
use App\Repositories\OrderRepository;

class OrderService
{

    public function __construct(
        private OrderRepository $orderRepository
    ) {

    }
    public function store(int $userId, array $items, float $value): Order
    {
        return $this->orderRepository->store($userId, $items, $value);
    }

    public function update(Order $order, array $data)
    {
        return $this->orderRepository->update($order, $data);
    }

    public function getOrder(int $orderId)
    {
        return $this->orderRepository->getOrder($orderId);
    }
}