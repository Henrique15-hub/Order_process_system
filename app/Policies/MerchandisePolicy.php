<?php

namespace App\Policies;

use App\Enums\RoleEnum;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class MerchandisePolicy
{
    public function create(User $user): Response
    {
        if ($user->role->value !== RoleEnum::Admin->value) {
            return Response::deny("Only users with Admin role can do this");
        }

        return Response::allow();
    }


    public function update(User $user): Response
    {
        if ($user->role->value != RoleEnum::Admin->value) {
            return Response::deny("Only users with Admin role can do this");
        }

        return Response::allow();
    }


    public function delete(User $user): Response
    {
        if ($user->role->value != RoleEnum::Admin->value) {
            return Response::deny("Only users with Admin role can do this");
        }

        return Response::allow();
    }

}