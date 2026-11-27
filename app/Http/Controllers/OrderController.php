<?php

namespace App\Http\Controllers;

use App\UseCase\Order\OrderBuildUseCase;
use Illuminate\Http\JsonResponse;


class OrderController extends Controller
{
    public function __construct(
        private OrderBuildUseCase $orderBuildUseCase
    ) {

    }
    public function store(): JsonResponse
    {

        return $this->orderBuildUseCase->execute(auth()->user());
    }
}
