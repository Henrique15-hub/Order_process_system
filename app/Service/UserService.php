<?php
namespace App\Service;

use App\Enums\RoleEnum;
use App\Exceptions\UserInvalidCredentialException;
use App\Repositories\UserRepository;

class UserService
{
    public function __construct(
        private UserRepository $repo,
    ) {
        //
    }
    public function store($data)
    {
        if (isset($data['cnpj']) && isset($data['email'])) {
            throw new UserInvalidCredentialException("You can't create an account with 'email 'and 'cpnj'.", 422);
        }

        isset($data['cnpj'])
            ? $data['role'] = RoleEnum::BusinessAccount->value
            : $data['role'] = RoleEnum::Client->value;

        $user = $this->repo->store($data);

        return $user;
    }
}
