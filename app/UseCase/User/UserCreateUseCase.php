<?php
namespace App\UseCase\User;

use App\Models\User;
use App\Service\CartService;
use App\Service\UserService;
use Illuminate\Support\Facades\DB;

class UserCreateUseCase
{
    public function __construct(
        private UserService $userService,
        private CartService $cartService
    ) {
        //
    }

    public function execute(array $data): User
    {
        return DB::transaction(function () use ($data) {
            $user = $this->userService->store($data);

            $this->cartService->store($user->id);

            return $user;
        });

    }
}
