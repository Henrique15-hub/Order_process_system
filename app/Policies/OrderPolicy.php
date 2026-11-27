<?php

namespace App\Policies;

use App\Enums\CartStatus;
use App\Enums\RoleEnum;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class OrderPolicy
{

    public function store(User $user): Response
    {
        if ($user->role === RoleEnum::Admin) {
            return Response::deny("Users with Admin role can't do this.", 401);
        }

        if ($user->cart->status->value === CartStatus::Closed->value) {
            return Response::deny("The cart is closed. To open the cart, pay or cancel the actual payment link");
        }
        return Response::allow();
    }

}