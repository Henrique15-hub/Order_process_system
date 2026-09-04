<?php
namespace App\Service;

use App\Exceptions\Auth\BusinessAccountEmailLoginNotAllowedException;
use App\Exceptions\UserInvalidCredentialException;
use App\Repositories\UserRepository;
use Illuminate\Support\Facades\Hash;

class AuthService
{
    public function __construct(
        private UserRepository $repo,
    ) {
        //
    }
    public function login($data)
    {
        isset($data['cnpj'])
            ? $user = $this->repo->findByCnpj($data['cnpj'])
            : $user = $this->repo->findByEmail($data['email']);


        if ($user->cnpj && !isset($data['cnpj'])) {
            throw new BusinessAccountEmailLoginNotAllowedException();
        }

        if (!Hash::check($data['password'], $user->password)) {
            throw new UserInvalidCredentialException();
        }

        $token = $user->createToken('auth_token')->plainTextToken;

        return [
            "userName" => $user->name,
            "token" => $token,
        ];
    }
}
