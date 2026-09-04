<?php
namespace App\Repositories;

use App\Models\User;

class UserRepository
{
    public function store($data)
    {
        return User::create($data);
    }

    public function findByEmail($email)
    {
        return User::where('email', $email)->first();
    }

    public function findByCnpj($cnpj)
    {
        return User::where('cnpj', $cnpj)->first();
    }
}
