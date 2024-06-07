<?php

namespace App\Http\Controllers;

use App\Http\Requests\AuthController\AuthenticateRequest;
use App\Http\Requests\AuthController\ChangePasswordRequest;
use App\Http\Requests\AuthController\CheckEmailRequest;
use App\Http\Requests\AuthController\CheckPasswordRequest;
use App\Http\Requests\AuthController\CheckUsernameRequest;
use App\Http\Requests\AuthController\InviteRequest;
use App\Http\Requests\AuthController\LoginRequest;
use App\Http\Requests\AuthController\PreRegisterRequest;
use App\Http\Requests\AuthController\RecoverPasswordRequest;
use App\Http\Requests\AuthController\RegisterRequest;
use App\Models\User;
use App\Services\AuthService;
// use App\Services\EmailService;
use App\Services\UserService;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    protected $AuthService;

    protected $UserService;

    // protected $EmailService;

    /**
     * Constructor of Board Task Controller
     */
    public function __construct(
        AuthService $AuthService,
        UserService $UserService,
        // emailService $EmailService,
    ) {
        $this->AuthService = $AuthService;
        $this->UserService = $UserService;
        // $this->EmailService = $EmailService;
    }

    public function register(RegisterRequest $request)
    {
        return $this->AuthService->register($request->validated());
    }

    public function preRegister(PreRegisterRequest $request)
    {
        return $this->AuthService->preRegister($request->validated());
    }

    public function inviteRegister(InviteRequest $request)
    {
        return $this->AuthService->inviteRegister($request->validated());
    }

    public function login(LoginRequest $request)
    {
        return $this->AuthService->login($request->validated());
    }

    public function loginAdmin(LoginRequest $request)
    {
        return $this->AuthService->loginAdmin($request->validated());
    }

    /**
     * Log the user out (Invalidate the token).
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout(Request $request)
    {
        return $this->AuthService->logout();
    }

    /**
     * Refresh a token.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function refresh(Request $request)
    {
        return $this->AuthService->refresh();
    }

    public function check(Request $request)
    {
        return ok('correcto');
    }

    public function recoverPassword(RecoverPasswordRequest $request)
    {
        return $this->AuthService->recoverPassword($request->validated());
    }

    public function recoverPasswordWEb(RecoverPasswordRequest $request)
    {
        return $this->AuthService->recoverPasswordWeb($request->validated());
    }

    public function changePassword(ChangePasswordRequest $request)
    {
        return $this->AuthService->changePassword($request->validated());
    }

    public function verifyTokenPassword($token_uuid)
    {
        return $this->AuthService->verifyTokenPassword($token_uuid);
    }

    public function authenticate(AuthenticateRequest $request)
    {
        return $this->AuthService->authenticate($request->validated());
    }

    public function checkEmail(CheckEmailRequest $request)
    {
        return ok('Email valido', $request->validated());
    }

    public function checkUsername(CheckUsernameRequest $request)
    {
        return ok('Nombre de usuario valido', $request->validated());
    }

    public function checkPassword(CheckPasswordRequest $request)
    {
        return $this->AuthService->checkPassword($request->validated());
    }

    public function checkWallet($wallet)
    {
        return $this->AuthService->checkWallet($wallet);
    }
}
