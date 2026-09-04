<?php

namespace App\Exceptions\Auth;

use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class BusinessAccountEmailLoginNotAllowedException extends Exception
{
    protected int $status;

    public function __construct(
        string $message = "You can't login in a business account with email.",
        int $code = 401
    ) {
        parent::__construct($message);
        $this->status = $code;
    }

    public function render(Request $request): JsonResponse
    {
        return response()->json([
            "sucess" => false,
            "message" => $this->getMessage(),
        ], $this->status);
    }
}