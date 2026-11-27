<?php
namespace App\Service;

use App\Exceptions\Merchandise\IdenticalMerchandiseNotAllowedException;
use App\Exceptions\QuantityTooHighException;
use App\Models\Merchandise;
use App\Repositories\MerchandiseRepository;
use Illuminate\Support\Str;

class MerchandiseService
{

    public function __construct(
        private MerchandiseRepository $merchandiseRepository,
    ) {

    }
    public function store(array $data): Merchandise
    {
        $DBMerchandise = $this->merchandiseRepository->findByNameAndPrice(data_get($data, 'name'), data_get($data, 'price'));

        if ($DBMerchandise) {
            throw new IdenticalMerchandiseNotAllowedException();
        }

        // error handling for ULID already existing in the DB
        while (True) {
            $data['public_id'] = "merc_" . Str::ulid();

            $merchandise = $this->merchandiseRepository->getMerchandise(data_get($data, 'public_id'));

            if (!$merchandise) {
                break;
            }
        }

        return $this->merchandiseRepository->store($data);

    }

    public function show(array $data)
    {
        return $this->merchandiseRepository->getMerchandise(data_get($data, 'public_id'));
    }

    public function update(array $data)
    {
        $merchandise = $this->merchandiseRepository->getMerchandise(data_get($data, 'public_id'));

        if (isset($data['public_id'])) {
            unset($data['public_id']);
        }

        return $this->merchandiseRepository->update($merchandise, $data);
    }

    public function delete(array $data)
    {
        $merchandise = $this->getMerchandise(data_get($data, 'public_id'));

        return $this->merchandiseRepository->delete($merchandise);
    }

    public function getMerchandise(string $publicId): Merchandise
    {
        return $this->merchandiseRepository->getMerchandise($publicId);
    }

    public function subtractFromStock(array $items)
    {
        foreach ($items as $item) {
            $merchandise = $this->merchandiseRepository->getMerchandise(data_get($item, 'public_id'));

            if ($merchandise->stock_quantity < data_get($item, 'quantity')) {
                throw new QuantityTooHighException("The quantity in stock is less than the quantity that user want to put in cart.");
            }

            $merchandise->stock_quantity -= data_get($item, 'quantity');

            $merchandise->save();
        }
    }

    public function addToStock(Merchandise $merchandise, int $quantityToAdd)
    {
        $merchandise->stock_quantity += $quantityToAdd;
        return $merchandise->save();
    }
}
