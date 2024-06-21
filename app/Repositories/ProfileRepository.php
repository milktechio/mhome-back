<?php

namespace App\Repositories;

use App\Models\Kyc;
use App\Models\Profile;
use App\Models\Qr;
use App\Models\User;
// use App\Services\QrService;
use Illuminate\Support\Facades\Auth;

class ProfileRepository
{

    // protected $QrService;

    // public function __construct(QrService $QrService)
    // {
    //     $this->QrService = $QrService;
    // }

    public function checkUrlInvite()
    {
        $user = auth()->user();
        $username = str_replace(' ', '%20', $user->username);
        $url_invite = env('URL_REFERRALS').'invite/'.$username;

        return ok('Url generada correctamente', [
            'url' => $url_invite,
            'qr' => QR::make([
                'content' => $url_invite,
            ], false),
        ]);
    }

    public function createProfile($data)
    {
        $user = auth()->user();
        $profile = Profile::create([
            'name' => $data['name'],
            'lastname' => $data['lastname'],
            'email' => $user->email,
            'mobile' => $data['mobile'],
            'profession' => $data['profession'],
        ]);
        $user->update([
            'profile_id' => $profile->id,
        ]);
        $user->save();

        return ok('Perfil creado correctamente', $profile);
    }

    public function updateProfile($profile, $data)
    {
        $user = User::where('profile_id', $profile->id)->first();

        if (isset($data['gender'])) {
            switch (intval($data['gender'])) {
                case 1: $data['gender'] = 'Hombre';
                    break;
                case 2: $data['gender'] = 'Mujer';
                    break;
                case 3: $data['gender'] = 'No binario';
                    break;
                case 4: $data['gender'] = 'Otro';
                    break;
                default:
                    break;
            }
        }

        $profile->update($data);

        return ok('Perfil actualizado correctamente', $profile);
    }

    public function deleteProfile($id)
    {
        $profile = Profile::find($id);
        $profile->delete();

        return ok('Perfil borrado correctamente');
    }

    public function changePhoto($file)
    {
        $profile = Profile::find(Auth::user()->profile_id);
        $profile->unlink('image_url');
        $profile->storeImage($file, 'image_url');

        return ok('Imagen guardada correctamente', $profile);
    }

    public function deleteWallet($user = null)
    {
        $user = $user ?? User::where('username', Auth::user()->username)->first();
        $user->eth_address = null;
        $user->save();

        return ok('Wallet eliminada correctamente');
    }
}
