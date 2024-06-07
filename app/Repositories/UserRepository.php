<?php

namespace App\Repositories;

use App\Models\Profile;
use App\Models\Rangue;
use App\Models\RangueHistory;
use App\Models\User;
use App\Services\ProfileService;
use App\Services\ProspectService;
use App\Services\RangueService;
use App\Traits\PaginateRepository;
use Auth;
use DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;

class UserRepository
{
    use PaginateRepository;

    // public function __construct(
    //     BusService $BusService,
    //     ProfileService $ProfileService,
    //     RangueService $RangueService,
    //     ProspectService $ProspectService
    // ) {
    //     $this->BusService = $BusService;
    //     $this->ProfileService = $ProfileService;
    //     $this->RangueService = $RangueService;
    //     $this->ProspectService = $ProspectService;
    // }

    public function all()
    {
        return ok('', User::with('roles')->get());
    }

    public function getByRangueNumber($number)
    {
        $query = User::select('users.id', 'users.rangue_id');
        $query->with('rangue:id,pool,number');
        $query->join('rangues', 'users.rangue_id', 'rangues.id');
        $query->where('rangues.number', '>=', $number);

        return ok('', $query->get());
    }

    public function store($data)
    {
        $profile = new Profile();
        $profile->name = $data['name'];
        $profile->email = $data['email'];
        $profile->lastname = $data['lastname'];
        $profile->profession = $data['profession'];
        $profile->save();

        $password = uniqid();
        $user = User::create([
            'username' => $data['username'],
            'email' => $data['email'],
            'password' => bcrypt($password),
            'is_active' => 1,
            'email_verified_at' => now(),
            'rangue_id' => Rangue::whereNull('number')->first()->id,
            'profile_id' => $profile->id,
        ])->fresh();
        $user->assignRole($data['role']);

        // $this->BusService->dispatch('post', 'email', '/send', [
        //     'emails' => json_encode([
        //         [
        //             'email' => $data['email'],
        //             'subject' => 'Haz sido registrado en wDAO EON',
        //             'data' => [
        //                 'body' => "
        //                     <p>Esta es tu contraseña: $password</p>
        //                     <p>Podras cambiarla en ajustes de tu perfil una vez inicies sesion</p>
        //                 ",
        //             ],
        //             'view' => 'base',
        //         ],

        //     ]),
        // ]);

        $this->RangueService->createAdvance($user);

        return ok('Usuario creado correctamente', User::with('roles', 'profile')->find($user->id));
    }

    public function search($data)
    {
        $users = User::with('roles', 'profile');

        foreach ($data as $key => $value) {
            if (Schema::hasColumn('users', $key)) {
                $users->where($key, $value);
            } else {
                return bad_request('La columna '.$key.' no existe', $data);
            }
        }

        return  ok('', $users->get());
    }

    public function find($id)
    {
        return ok('usuario encontrado', User::with('roles')->find($id));
    }

    public function update($data, $user, $mine = false)
    {
        $user->username = $data['username'];
        $user->email = $data['email'];
        if (isset($data['password'])) {
            $user->password = Hash::make($data['password']);
        }
        unset($user->roles);

        if (isset($data['rangue_id']) && ! $mine) {
            $oldRangue = $user->rangue_id;
            $user->rangue_id = $data['rangue_id'];
            if ($oldRangue != $user->rangue_id) {
                $rangue = Rangue::find($user->rangue_id);
                // $this->BusService->dispatch('post', 'plan', '/rangue/update', [
                //     'user_id' => $user->id,
                //     'rangue' => $rangue,
                //     'next_rangue' => $rangue->next,
                // ]);

                RangueHistory::create([
                    'rangue_id' => $user->rangue_id,
                    'user_id' => $user->id,
                ]);
            }
        }

        $auth = User::find(Auth::user()->id);
        if (! $mine && $auth->hasRole('administracion')) {
            $user->syncRoles([$data['role']]);
        }
        $user->save();

        return ok('Usuario editado correctamente', User::with('profile', 'roles', 'rangue')->find($user->id));
    }

    public function destroy($user)
    {
        $user->delete();

        return ok('Usuario eliminado correctamente');
    }

    public function unlock($user)
    {
        $user->block_until = null;
        $user->login_attemps = 0;
        $user->save();

        return ok('El usuario ya puede iniciar sesion', User::with('roles', 'profile')->find($user->id));
    }

    public function ban($user)
    {
        $user->is_active = 0;
        $user->save();

        return ok('El usuario ya no puede iniciar sesion', User::with('roles', 'profile')->find($user->id));
    }

    public function unban($user)
    {
        $user->is_active = 1;
        $user->save();

        return ok('El usuario ya puede iniciar sesion', User::with('roles', 'profile')->find($user->id));
    }

    public function userExists($username)
    {
        $user = User::where('username', $username)->first() ?? false;

        if (! $user || ! $user->hasRole('usuario')) {
            return not_found('No se encuentra un usuario con esta url');
        }

        $response = $this->ProspectService->store(['username' => $username]);

        return ok('', [
            'username' => $user->profile->name,
            'prospect' => $response->original['data'],
        ]);
    }

    public function sponsor($user, $data)
    {
        $user->sponsor_id = $data['sponsor_id'];
        $user->save();

        return ok('Sponsor actualizado correctamente', $user);
    }

    public function unDelete($user_id)
    {
        $user = User::withTrashed()->find($user_id) ?? false;

        if (! $user) {
            return not_found('El usuario no existe en la base de datos');
        }

        $DELETED = '_DELETED_';

        $user->email = explode($DELETED, $user->email)[0];
        $user->username = explode($DELETED, $user->username)[0];
        $user->deleted_at = null;
        $user->save();

        $profile = Profile::withTrashed()->find($user->profile_id);
        $profile->email = explode($DELETED, $profile->email)[0];
        $profile->deleted_at = null;
        $profile->save();

        $update = [
            'deleted_at' => null,
        ];
        DB::table('rangue_histories')->where('user_id', $user->id)->update($update);
        DB::table('kycs')->where('user_id', $user->id)->update($update);

        return ok('Usuario listo', $user);
    }

    public function withdrawBalance($data, $user = null)
    {
        $comission = 0;
        $saldo = 0;
        $rank = 0;

        $user = $user ?? Auth::user();

        if (isset($data['comission'])) {
            if ($user->metadata) {
                $metadata = $user->metadata;
                $comission = $metadata['comission'] ?? 0;
                $metadata['comission'] = 0;
                $user->metadata = $metadata;
            } else {
                $comission = 0;
            }
        }

        if (isset($data['saldo'])) {
            if ($user->metadata) {
                $metadata = $user->metadata;
                $saldo = $metadata['saldo'] ?? 0;
                $metadata['saldo'] = 0;
                $user->metadata = $metadata;
            } else {
                $saldo = 0;
            }
        }

        if (isset($data['rank'])) {
            if ($user->metadata) {
                $metadata = $user->metadata;
                $rank = $metadata['rank'] ?? 0;
                $metadata['rank'] = 0;
                $user->metadata = $metadata;
            } else {
                $rank = 0;
            }
        }

        unset($user->roles);
        $user->save();

        return ok('saldos actualizados correctamente', [
            'comission' => $comission,
            'saldo' => $saldo,
            'rank' => $rank,
        ]);
    }

    public function wallets()
    {
        $wallets = User::where('swap', 0)->pluck('eth_address')->unique()->filter(function ($wallet) {
            return $wallet;
        });

        return ok('', $wallets->values());
    }

    public function walletsUpdate($data)
    {
        $wallets = json_decode($data['wallets']);
        $result = User::where('swap', 0)->whereIn('eth_address', $wallets)->update([
            'swap' => 1,
        ]);

        return ok('', $wallets);
    }
}
