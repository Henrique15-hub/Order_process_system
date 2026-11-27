<?php

namespace App\Policies;

use App\Enums\RoleEnum;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class UserPolicy
{

    public function index(User $user): Response
    {
        if ($user->role !== RoleEnum::Admin) {
            return Response::deny();
        }

        return Response::allow();
    }


    public function show(User $user): Response
    {
        if ($user->role !== RoleEnum::Admin) {
            return Response::deny();
        }

        return Response::allow();
    }

    public function update(User $user): Response
    {
        if ($user->role !== RoleEnum::Admin) {
            return Response::deny();
        }

        return Response::allow();
    }

    public function delete(User $user): Response
    {
        if ($user->role !== RoleEnum::Admin) {
            return Response::deny();
        }

        return Response::allow();
    }


}