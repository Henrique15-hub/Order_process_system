<?php

namespace App\Http\Controllers;

use App\Http\Requests\Auth\AuthLoginRequest;
use App\Service\AuthService;

class AuthController extends Controller
{
    public function __construct(
        private AuthService $authService
    ) {
        //
    }

    public function login(AuthLoginRequest $request)
    {
        $loginInfo = $this->authService->login($request->validated());

        return response()->json([
            'success' => true,
            'message' => 'Logged successfully',
            'status' => 200,
            'data' => [
                'user_name' => data_get($loginInfo, 'user_name'),
                'token' => data_get($loginInfo, 'token'),
            ],
        ]);
    }
}