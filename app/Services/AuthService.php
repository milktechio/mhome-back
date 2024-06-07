<?php

namespace App\Services;

use App\Repositories\AuthRepository;
use App\Repositories\UserRepository;

class AuthService
{
    protected $AuthRepository;

    protected $UserRepository;

    public function __construct(AuthRepository $AuthRepository, UserRepository $UserRepository)
    {
        $this->AuthRepository = $AuthRepository;
        $this->UserRepository = $UserRepository;
    }

    public function register($data)
    {
        return $this->AuthRepository->register($data);
    }

    public function login($data)
    {
        return $this->AuthRepository->login($data, function ($user) {
            return $user->hasRole('usuario');
        });
    }

    public function loginAdmin($data)
    {
        return $this->AuthRepository->login($data, function ($user) {
            return ! $user->hasRole('usuario');
        });
    }

    public function logout()
    {
        return $this->AuthRepository->logout();
    }

    public function refresh()
    {
        return $this->AuthRepository->refresh();
    }

    public function recoverPassword($data)
    {
        return $this->AuthRepository->recoverPassword($data);
    }

    public function recoverPasswordWeb($data)
    {
        return $this->AuthRepository->recoverPasswordWeb($data);
    }

    public function changePassword($data)
    {
        return $this->AuthRepository->changePassword($data);
    }

    public function authenticate($data)
    {
        return $this->AuthRepository->authenticate($data);
    }

    public function verifyTokenPassword($token)
    {
        return $this->AuthRepository->verifyTokenPassword($token);
    }

    public function preRegister($data)
    {
        return $this->AuthRepository->preRegister($data);
    }

    public function inviteRegister($data)
    {
        return $this->AuthRepository->inviteRegister($data);
    }

    public function checkPassword($data)
    {
        return $this->AuthRepository->checkPassword($data);
    }

    public function checkWallet($wallet)
    {
        return $this->AuthRepository->checkWallet($wallet);
    }
}
