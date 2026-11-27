<?php

namespace App\Exceptions\Cart;

use Exception;
use Illuminate\Http\JsonResponse;

class CartEmptyException extends Exception
{

    protected int $status;

    public function __construct(
        string $message = "You can't create an order with an empty cart!",
        int $code = 403
    ) {
        parent::__construct($message);
        $this->status = $code;
    }

    public function render(): JsonResponse
    {
        return response()->json([
            "message" => $this->getMessage(),
        ], $this->status);
    }
}