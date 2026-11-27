<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class QuantityTooHighException extends Exception
{
    protected int $status;

    public function __construct(
        string $message = "The quantity is too high",
        int $code = 422
    ) {
        parent::__construct($message);
        $this->status = $code;
    }

    public function render(Request $request): JsonResponse
    {
        return response()->json([

            "message" => $this->getMessage(),
        ], $this->status);
    }
}
