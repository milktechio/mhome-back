<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserController\SponsorRequest;
use App\Http\Requests\UserController\StoreRequest;
use App\Http\Requests\UserController\UpdateMineRequest;
use App\Http\Requests\UserController\UpdateRequest;
use App\Http\Requests\UserController\WalletsRequest;
use App\Http\Requests\UserController\WithdrawBalanceRequest;
use App\Models\User;
use App\Services\UserService;
use Auth;
use Illuminate\Http\Request;

class UserController extends Controller
{
    protected $UserService;

    /**
     * Constructor of Board Task Controller
     */
    public function __construct(UserService $UserService)
    {
        $this->UserService = $UserService;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        return $this->UserService->query($request->query());
    }

    public function getByRangueNumber($number)
    {
        return $this->UserService->getByRangueNumber($number);
    }

    /**
     * Crea un nuevo usuario.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param    $request 'name'
     * @param    $request 'email'
     * @param    $request 'password'
     * @param    $request 'rol_id'
     * @param    $request 'roles'
     * @return \Illuminate\Http\Response
     */
    public function store(StoreRequest $request)
    {
        return $this->UserService->store($request->validated());
    }

    /**
     * Muestra un usuario con un id dado.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(User $user)
    {
        return ok('', User::with('profile', 'roles', 'kyc')->find($user->id));
    }

    /**
     * Muestra un usuario con un id dado.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function myUser()
    {
        $user = Auth::user();

        $user = User::with('profile.sponsor', 'roles', 'kyc')->find($user->id);

        return ok('', $user);
    }

    /*
     * Actualiza un usuario con un id dado.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @param  $request 'name'
     * @param  $request 'email'
     * @param  $request 'password'
     * @param  $request 'rol_id'
     * @param  $request 'roles'
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateRequest $request, User $user)
    {
        return $this->UserService->update($request->validated(), $user);
    }

    public function updateMine(UpdateMineRequest $request)
    {
        return $this->UserService->updateMine($request->validated());
    }

    public function withdrawBalance(WithdrawBalanceRequest $request)
    {
        return $this->UserService->withdrawBalance($request->validated());
    }

    public function withdrawBalanceUser(WithdrawBalanceRequest $request, User $user)
    {
        return $this->UserService->withdrawBalance($request->validated(), $user);
    }

    /**
     * Borra un usuario dado un id.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, User $user)
    {
        return $this->UserService->destroy($user);
    }

    /**
     * Si un usuario se bloquea, el admin lo puede desbloquear.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function unlock(Request $request, User $user)
    {
        return $this->UserService->unlock($user);
    }

    public function ban(Request $request, User $user)
    {
        return $this->UserService->ban($user);
    }

    public function unban(Request $request, User $user)
    {
        return $this->UserService->unban($user);
    }

    public function sponsor(SponsorRequest $request, User $user)
    {
        return $this->UserService->sponsor($user, $request->validated());
    }

    public function rangueAdvance()
    {
        return ok('', User::select('id', 'rangue_id', 'sponsor_id')->get());
    }

    public function userExists($username)
    {
        return $this->UserService->userExists($username);
    }

    public function trash(Request $request)
    {
        return $this->UserService->trash($request->query());
    }

    public function unDelete($user_id)
    {
        return $this->UserService->unDelete($user_id);
    }

    public function wallets()
    {
        return $this->UserService->wallets();
    }

    public function walletsUpdate(WalletsRequest $request)
    {
        return $this->UserService->walletsUpdate($request->validated());
    }
}
