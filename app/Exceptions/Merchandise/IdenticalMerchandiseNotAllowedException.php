<?php

namespace App\Exceptions\Merchandise;

use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class IdenticalMerchandiseNotAllowedException extends Exception
{

    protected int $status;

    public function __construct(
        string $message = "Identical merchandise not allowed",
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