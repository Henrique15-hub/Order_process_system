<?php
namespace App\Service;

use App\Repositories\MerchandiseRepository;
class MerchandiseService
{

    public function __construct(
        private MerchandiseRepository $repo,
    ) {

    }
    public function store($data, $userId)
    {
        $data['user_id'] = $userId;

        return $this->repo->store($data);
    }
}
