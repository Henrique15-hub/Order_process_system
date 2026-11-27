<?php
namespace App\Service;

use App\Enums\RoleEnum;
use App\Exceptions\ForbbidenException;
use App\Repositories\UserRepository;
use RuntimeException;

class UserService
{
    public function __construct(
        private UserRepository $userRepository
    ) {

    }

    public function store(array $data)
    {
        $data['role'] = RoleEnum::Client->value;

        return $this->userRepository->store($data);
    }


    public function delete(int $userId)
    {
        $deleted = $this->userRepository->delete($userId);

        if (!$deleted) {
            throw new RuntimeException();
        }

        return $deleted;

    }

    public function indexAdmin()
    {
        return $this->userRepository->indexAdmin();
    }

    public function showAdmin(array $data)
    {
        $id = data_get($data, 'id');
        return $this->userRepository->showAdmin($id);
    }

    public function updateAdmin(array $data)
    {
        $id = data_get($data, 'id');

        $user = $this->userRepository->findByid($id);

        if ($user->role === RoleEnum::Admin) {
            throw new ForbbidenException("You can't delete an admin!");
        }

        unset($data['id']);

        return $this->userRepository->updateAdmin($id, $data);
    }

    public function deleteAdmin(array $data)
    {
        $id = data_get($data, 'id');

        $user = $this->userRepository->findByid($id);

        if ($user->role === RoleEnum::Admin) {
            throw new ForbbidenException("You can't delete an admin!");
        }

        $deleted = $this->userRepository->deleteAdmin($id);

        if (!$deleted) {
            throw new RuntimeException();
        }

        return true;
    }

}