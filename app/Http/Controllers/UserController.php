<?php

namespace App\Http\Controllers;

use App\Http\Requests\User\UserStoreRequest;
use App\UseCase\User\UserCreateUseCase;

class UserController extends Controller
{
    public function __construct(
        private UserCreateUseCase $userStoreUseCase
    ) {
        //
    }
    public function store(UserStoreRequest $request)
    {
        $userInfo = $this->userStoreUseCase->execute($request->validated());

        return response()->json([
            'success' => true,
            'message' => 'Account created successfully',
            'status' => 200,
            'data' => [
                'Account Name' => $userInfo['name'],
                'Account Role' => $userInfo['accountRole'],
            ],
        ]);
    }
}