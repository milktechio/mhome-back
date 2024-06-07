<?php

namespace App\Services;

use App\Models\User;
use App\Repositories\UserRepository;
use Auth;

class UserService
{
    protected $UserRepository;

    public function __construct(UserRepository $UserRepository)
    {
        $this->UserRepository = $UserRepository;
    }

    public function store($data)
    {
        return $this->UserRepository->store($data);
    }

    public function query($data)
    {
        return $this->UserRepository->query(User::class, $data, function ($query) {
            $query->with('profile', 'roles');

            return $query;
        });
    }

    public function trash($data)
    {
        return $this->UserRepository->query(User::class, $data, function ($query) {
            $query->onlyTrashed();
            $query->with('merge_to.user');

            return $query;
        }, function ($data) {
            return $data->map(function ($user) {
                $user->makeVisible(['deleted_at']);

                return $user;
            });
        });
    }

    public function getByRangueNumber($rangueNumber)
    {
        return $this->UserRepository->getByRangueNumber($rangueNumber);
    }

    public function find($id)
    {
        return $this->UserRepository->find($id);
    }

    public function update($data, $user)
    {
        return $this->UserRepository->update($data, $user);
    }

    public function updateMine($data)
    {
        $user = Auth::user();

        return $this->UserRepository->update($data, $user, true);
    }

    public function destroy($user)
    {
        return $this->UserRepository->destroy($user);
    }

    public function unlock($user)
    {
        return $this->UserRepository->unlock($user);
    }

    public function ban($user)
    {
        return $this->UserRepository->ban($user);
    }

    public function unban($user)
    {
        return $this->UserRepository->unban($user);
    }

    public function userExists($username)
    {
        return $this->UserRepository->userExists($username);
    }

    public function sponsor($user, $data)
    {
        return $this->UserRepository->sponsor($user, $data);
    }

    public function unDelete($user_id)
    {
        return $this->UserRepository->unDelete($user_id);
    }

    public function withdrawBalance($data, $user = null)
    {
        return $this->UserRepository->withdrawBalance($data, $user);
    }

    public function wallets()
    {
        return $this->UserRepository->wallets();
    }

    public function walletsUpdate($data)
    {
        return $this->UserRepository->walletsUpdate($data);
    }
}
