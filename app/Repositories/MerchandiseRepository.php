<?php
namespace App\Repositories;

use App\Models\Merchandise;

class MerchandiseRepository
{

    public function store($data)
    {
        return Merchandise::create($data);
    }

    public function findByNameAndPrice($name, $price)
    {
        return Merchandise::where('name', $name)
            ->where('price', $price)
            ->first();
    }

    public function update(Merchandise $merchandise, array $data)
    {
        $merchandise->update($data);

        return $merchandise->fresh();
    }
    public function delete(Merchandise $merchandise)
    {
        return $merchandise->delete();
    }

    public function getMerchandise(string $publicId)
    {
        return Merchandise::select([
            "id",
            "public_id",
            "name",
            "price",
            "stock_quantity"
        ])->where("public_id", $publicId)->first();
    }
}
