<?php

namespace App\Http\Controllers;

use App\Http\Requests\Auth\AuthLoginRequest;
use App\UseCase\Auth\AuthLoginUseCase;

class AuthController extends Controller
{
    public function __construct(
        private AuthLoginUseCase $authLoginUseCase
    ) {
        //
    }

    public function login(AuthLoginRequest $request)
    {
        $loginInfo = $this->authLoginUseCase->execute($request->validated());

        return response()->json([
            'success' => true,
            'message' => 'Logged successfully',
            'status' => 200,
            'data' => [
                'User name' => $loginInfo['userName'],
                'Token' => $loginInfo['token'],
            ],
        ]);
    }
}
