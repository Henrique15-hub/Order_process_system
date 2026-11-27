<?php
namespace App\Repositories;


use App\Models\User;

class UserRepository
{
    public function store(array $data)
    {
        return User::create($data);
    }

    public function findByEmail(string $email)
    {
        return User::where('email', $email)->first();
    }

    public function findByid(int $id)
    {
        return User::where('id', $id)->first();
    }

    public function delete(int $userId)
    {
        $user = $this->findById($userId);

        return $user->delete();
    }

    public function indexAdmin()
    {
        return User::query()->paginate(15);
    }

    public function showAdmin(int $userId)
    {
        return $this->findById($userId);
    }

    public function updateAdmin(int $userId, array $data)
    {
        $user = $this->findById($userId);

        $user->update($data);

        return $user;
    }

    public function deleteAdmin(int $userId)
    {
        $user = $this->findById($userId);

        return $user->delete();
    }
}
