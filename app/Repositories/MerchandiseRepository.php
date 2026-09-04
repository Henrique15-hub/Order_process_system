<?php
namespace App\Repositories;

use App\Models\Merchandise;
class MerchandiseRepository
{
    public function store($data)
    {
        return Merchandise::create($data);
    }
}