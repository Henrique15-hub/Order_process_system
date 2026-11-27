<?php
namespace App\Exceptions;

use Exception;
use Illuminate\Http\JsonResponse;

class ForbbidenException extends Exception
{
    protected int $status;

    public function __construct(
        string $message = "You are not allowed to access this!",
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