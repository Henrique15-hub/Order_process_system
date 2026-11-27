<?php

namespace App\Http\Controllers;

use App\Http\Requests\User\UserStoreRequest;
use App\Service\UserService;
use App\UseCase\User\UserCreateUseCase;

class UserController extends Controller
{
    public function __construct(
        private UserCreateUseCase $userStoreUseCase,
        private UserService $userService
    ) {
        //
    }
    public function store(UserStoreRequest $request)
    {
        $user = $this->userStoreUseCase->execute($request->validated());

        return response()->json([
            'message' => 'Account created successfully',
            'data' => [
                "name" => data_get($user, 'name'),
                "email" => data_get($user, 'email'),
                "role" => data_get($user, 'role'),
            ],
        ], 201);
    }

    public function delete()
    {
        $this->userService->delete(auth()->id());

        return response()->json([
            'message' => 'User and cart deleted successfully!'
        ]);

    }
}
