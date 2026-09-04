<?php
namespace App\UseCase\User;

use App\Repositories\CartRepository;
use App\Service\UserService;
class UserCreateUseCase
{
    public function __construct(
        private UserService $userService,
        private CartRepository $cartRepository,
    ) {
        //
    }

    public function execute($data)
    {
        $user = $this->userService->store($data);

        $this->cartRepository->store($user->id);

        return [
            "name" => $user->name,
            "accountRole" => $user->role,
        ];
    }
}