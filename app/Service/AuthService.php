<?php
namespace App\Service;

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
        $user = $this->repo->findByEmail(data_get($data, 'email'));

        if (!Hash::check(data_get($data, 'password'), $user->password)) {
            throw new UserInvalidCredentialException();
        }

        $token = $user->createToken('auth_token')->plainTextToken;

        return [
            'user_name' => $user->name,
            'token' => $token,
        ];
    }
}
