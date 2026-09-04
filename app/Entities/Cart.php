<?php
namespace App\Entities;
class Cart
{
    public function __construct(
        private readonly int $userId,
        private array $items = [],
        private float $actual_value = 0,
    ) {

    }

    public function addItemToCart()
    {

    }
}
