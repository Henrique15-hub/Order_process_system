<?php
namespace App\UseCase\Auth;

use App\Service\AuthService;
class AuthLoginUseCase
{
    public function __construct(
        private AuthService $service
    ) {
        //
    }

    public function execute($data)
    {
        return $this->service->login($data);
    }
}