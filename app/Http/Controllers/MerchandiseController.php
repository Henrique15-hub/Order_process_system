<?php

namespace App\Http\Controllers;

use App\Http\Requests\Merchandise\MerchandisePublicIdRequest;
use App\Http\Requests\Merchandise\MerchandiseStoreRequest;
use App\Http\Requests\Merchandise\MerchandiseUpdateRequest;
use App\Service\MerchandiseService;

class MerchandiseController extends Controller
{
    public function __construct(
        private MerchandiseService $merchandiseService,
    ) {
        //
    }

    public function store(MerchandiseStoreRequest $request)
    {

        $merchandise = $this->merchandiseService->store($request->validated());

        return response()->json([
            'message' => 'Merchandise created successfully',
            'data' => [
                'public_id' => $merchandise->public_id,
                'name' => $merchandise->name,
                'price' => $merchandise->price,
                'stock_quantity' => $merchandise->stock_quantity,
            ],
        ], 201);

    }

    public function show(MerchandisePublicIdRequest $request)
    {

        $merchandise = $this->merchandiseService->show($request->validated());

        return response()->json([
            'message' => 'Showing the merchandise',
            'data' => $merchandise
        ]);
    }

    public function update(MerchandiseUpdateRequest $request)
    {
        $merchandise = $this->merchandiseService->update($request->validated());

        return response()->json([
            'message' => 'Merchandise updated',
            'data' => $merchandise
        ]);
    }

    public function delete(MerchandisePublicIdRequest $request)
    {
        $this->merchandiseService->delete($request->validated());

        return response()->json([
            'message' => 'Merchandise deleted successfully'
        ]);
    }
}