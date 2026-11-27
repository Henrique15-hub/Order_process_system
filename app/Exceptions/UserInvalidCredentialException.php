<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class UserInvalidCredentialException extends Exception
{

    protected int $status;

    public function __construct(
        string $message = "Invalid credentials",
        int $code = 400
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
