<?php

namespace App\Http\Controllers;

use App\Http\Requests\Merchandise\MerchandiseStoreRequest;
use App\Service\MerchandiseService;

class MerchandiseController extends Controller
{
    public function __construct(
        private MerchandiseService $merchandiseService
    ) {
        //
    }
    public function store(MerchandiseStoreRequest $request)
    {
        $merchandise = $this->merchandiseService->store($request->validated(), auth()->id());

        return response()->json([
            'success' => true,
            'message' => 'Merchandise created successfully',
            'data' => [
                'name' => $merchandise->name,
                'price' => $merchandise->price,
                'stock quantity' => $merchandise->stock_quantity,
            ],
        ]);

    }
}