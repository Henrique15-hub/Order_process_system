<?php

namespace App\Http\Controllers;

use App\Http\Requests\AdminUser\UserIdRequiredRequestAdmin;
use App\Http\Requests\AdminUser\UserUpdateRequestAdmin;
use App\Service\UserService;



class UserControllerAdmin extends Controller
{
    public function __construct(
        private UserService $userService
    ) {

    }
    public function index()
    {
        $userCollection = $this->userService->indexAdmin();

        return response()->json([
            'message' => 'Showing all users by page.',
            'data' => $userCollection
        ]);
    }

    public function show(UserIdRequiredRequestAdmin $request)
    {
        $user = $this->userService->showAdmin($request->validated());

        return response()->json([
            'message' => 'Showing the user.',
            'data' => $user
        ]);
    }

    public function update(UserUpdateRequestAdmin $request)
    {
        $user = $this->userService->updateAdmin($request->validated());

        return response()->json([
            'message' => 'User updated.',
            'data' => $user
        ]);
    }

    public function delete(UserIdRequiredRequestAdmin $request)
    {
        $this->userService->deleteAdmin($request->validated());

        return response()->json([
            'message' => 'User deleted successfully.'
        ]);
    }

}