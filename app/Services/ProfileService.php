<?php

namespace App\Services;

use App\Repositories\ProfileRepository;
use Auth;

class ProfileService
{
    protected $ProfileRepository;

    public function __construct(ProfileRepository $profileRepository)
    {
        $this->profileRepository = $profileRepository;
    }

    public function checkUrlInvite()
    {
        return $this->profileRepository->checkUrlInvite();
    }

    public function createProfile($data)
    {
        return $this->profileRepository->createProfile($data);
    }

    public function updateMyProfile($data)
    {
        $user = Auth::user();

        return $this->profileRepository->updateProfile($user->profile, $data);
    }

    public function updateProfile($profile, $data)
    {
        return $this->profileRepository->updateProfile($profile, $data);
    }

    public function deleteProfile($id)
    {
        return $this->profileRepository->deleteProfile($id);
    }

    public function changePhoto($file)
    {
        return $this->profileRepository->changePhoto($file);
    }

    public function deleteWallet($user = null)
    {
        return $this->profileRepository->deleteWallet($user);
    }
}
