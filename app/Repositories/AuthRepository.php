<?php

namespace App\Repositories;

use App\Models\Profile;
use App\Models\Rangue;
use App\Models\User;
use App\Services\EmailService;
use App\Services\ProfileService;
use App\Services\RangueService;
use Auth;
use Carbon\Carbon;
use Firebase\JWT\JWT;
use Illuminate\Support\Facades\Hash;
use Str as UUID;

class AuthRepository
{
    // public function __construct(
    //     ProfileService $ProfileService,
    //     EmailService $EmailService,
    //     BusService $BusService,
    //     RangueService $RangueService
    // ) {
    //     $this->BusService = $BusService;
    //     $this->EmailService = $EmailService;
    //     $this->ProfileService = $ProfileService;
    //     $this->RangueService = $RangueService;
    //     $this->send = '/send';
    // }

    public function checkWallet($wallet)
    {
        $users = User::where('eth_address', $wallet)->get()->count();

        if ($users) {
            return ok('La wallet existe');
        }

        return not_found('La wallet no existe');
    }

    public function register($data)
    {
        $user = $this->completeRegister($data);
        // $this->RangueService->createAdvance($user);
        // $this->EmailService->sendVerification($user->email, $data['name']);

        return  $this->login($data, $user->HasRole('user'));
    }

    protected function checkUserBlock(&$user, &$message)
    {
        if ($user && $user->login_attemps >= 10 && $user->block_until > now()) {
            $message = 'Esta cuenta estará bloqueada de acceso hasta '.$user->block_until;
            $user = false;
        }
    }

    protected function addLoginAttemps($user, &$message)
    {
        $user->login_attemps += 1;
        if ($user->login_attemps >= 10) {
            $user->block_until = Carbon::now()->addDays(1);
            $message = 'Superaste el numero de intentos, la cuenta estará bloqueada 24 horas';
        }
        $user->save();
    }

    public function login($login_credentials, $roleCheck)
    {
        $message = 'Tus claves de acceso son incorrectas.';
        $user = User::with('roles', 'profile')->where('is_active', 1);
        $user = $user->where('email', $login_credentials['email']);

        $language = $login_credentials['language'] ?? false;

        $user = $user->get()->first() ?? false;

        $this->checkUserBlock($user, $message);

        if ($user /*&& $roleCheck($user)*/) {
            if ($user->checkPassword($login_credentials['password'])) {
                if ($language) {
                    $user->profile->language = $language;
                    $user->profile->save();
                }

                $today = strtotime('now');
                $exp = strtotime('+1 days');

                JWT::$leeway = 180;
                $userArray = $user->toJWTarray();
                $key = env('JWT_SECRET');
                $payload = [
                    'iss' => $login_credentials['email'],
                    'jti' => uniqid().uniqid(),
                    'iat' => strtotime('today'),
                    'ttl' => $exp - $today,
                    'exp' => $exp,
                    'sub' => $userArray,

                ];
                $token = JWT::encode($payload, $key, 'HS256');

                if ($token) {
                    $user->saveToken($token);
                    $user->login_attemps = 0;
                    $user->block_until = null;
                    $user->save();

                    return ok('Sesion iniciada correctamente', $token);
                }
            } else {
                $this->addLoginAttemps($user, $message);
            }
        }

        return unauthorized($message);
    }

    public function logout()
    {
        Auth::user()->token->revoke();

        return ok('Sesion cerrada correctamente');
    }

    public function refresh()
    {
        $user = Auth::user();

        $today = strtotime('now');
        $exp = strtotime('+1 days');

        JWT::$leeway = 180;
        $key = env('JWT_SECRET');
        $payload = [
            'iss' => $user->email,
            'jti' => uniqid().uniqid(),
            'iat' => strtotime('today'),
            'ttl' => $exp - $today,
            'exp' => $exp,
            'sub' => $user,

        ];
        $token = JWT::encode($payload, $key, 'HS256');
        $user->saveToken($token);

        return ok('Token refrescado correctamente', $token);
    }

    public function recoverPassword($data)
    {
        $user = User::whereEmail($data['email'])->first() ?? false;

        $token = (string) UUID::uuid();
        $token_expire_at = date('Y-m-d H:i:s', strtotime('now + 1 hours'));

        $user->token = $token;
        $user->token_expire_at = $token_expire_at;
        $user->save();

        $url = env('URL_REFERRALS').'change-password/'.$token;
        // $this->BusService->dispatch('post', 'email', $this->send, [
        //     'emails' => json_encode([
        //         [
        //             'email' => $data['email'],
        //             'subject' => 'Solicitud para cambiar contraseña',
        //             'data' => [
        //                 'body' => '<h1>Da click en el boton para cambiar tu contraseña</h1>',
        //                 'btn_url' => $url,
        //                 'btn_name' => 'Cambiar contraseña',
        //             ],
        //             'view' => 'base',
        //         ],

        //     ]),
        // ]);

        return ok('correo enviado');
    }

    public function recoverPasswordWeb($data)
    {
        $user = User::whereEmail($data['email'])->first() ?? false;

        $token = (string) UUID::uuid();
        $token_expire_at = date('Y-m-d H:i:s', strtotime('now + 1 hours'));

        $user->token = $token;
        $user->token_expire_at = $token_expire_at;
        $user->save();

        // $this->BusService->dispatch('post', 'email', $this->send, [
        //     'emails' => json_encode([
        //         [
        //             'email' => $data['email'],
        //             'subject' => 'Solicitud para cambiar contraseña',
        //             'data' => [
        //                 'body' => "<h1>$token</h1><p>Copea esta cadena y pegala en tu navegador</p>",
        //             ],
        //             'view' => 'base',
        //         ],

        //     ]),
        // ]);

        return ok('correo enviado');
    }

    public function changePassword($data)
    {
        $user = User::where('token', $data['token'])
        ->where('token_expire_at', '>=', now())
        ->first() ?? false;

        if (! $user) {
            return not_found('usuario no encontrado');
        }

        $user->password = bcrypt($data['password']);
        $user->token = null;
        $user->token_expire_at = null;
        $user->save();

        return ok('contraseña cambiada correctamente');
    }

    public function verifyTokenPassword($token)
    {
        $user = User::where('token', $token)
        ->where('token_expire_at', '>=', now())
        ->first() ?? false;

        if ($user) {
            $data = [
                'url' => 'appeonpassword://changePassword/'.$token,
                'btn_text' => 'Abrir app',
                'message' => 'Redirigiendo... Si la app no abre automaticamente usa el siguiente botón',
            ];

            return ok('redirect', $data);
        }

        return expired('El token ha expirado');
    }

    public function preRegister($data)
    {
        $userSponsor = auth()->user();
        $user = User::create([
            'username' => $data['username'],
            'email' => $data['email'],
            'password' => bcrypt(uniqid()),
            'is_active' => 0,
            'email_verified_at' => now(),
            'rangue_id' => Rangue::whereNull('number')->first()->id,
            'sponsor_id' => $userSponsor->id,
        ]);

        $user->assignRole('usuario');

        JWT::$leeway = 180;
        $key = env('JWT_SECRET');
        $payload = [
            'prerregister' => $user,
            'usr' => $userSponsor->id,
            'name' => $userSponsor->profile->name,
        ];
        $jwt = JWT::encode($payload, $key, 'HS256');
        $url = env('URL_REFERRALS')."prerregister/$jwt";
        $email = $data['email'];
        // $response = $this->BusService->dispatch('post', 'email', $this->send, [
        //     'emails' => json_encode([
        //         [
        //             'email' => $email,
        //             'subject' => 'asunto',
        //             'data' => [
        //                 'body' => '<h1>Bienvenido a eon, por favor termina tu registro en el siguiente enlace</h1>',
        //                 'btn_url' => $url,
        //                 'btn_name' => 'Registrarme',
        //             ],
        //             'view' => 'base',
        //         ],

        //     ]),
        // ]);

        // return ok('usuario prerregistrado correctamente', $response);
    }

    public function inviteRegister($data)
    {
        $user = User::where('email', $data['email'])->first() ?? false;
        if ($user) {
            if ($user->is_active != 0) {
                return conflict('usuario ya registrado');
            }

            $user = $this->completePreregister($data, $user);
            $this->EmailService->sendWelcome($user->email, $data['name']);
        } else {
            $data['user_id'] = User::where('username', $data['sponsor'])->first()->id;
            unset($data['sponsor']);
            $user = $this->completeRegister($data);
            $this->EmailService->sendVerification($user->email);
        }

        $user = $user->getFresh();
        $response = $this->RangueService->createAdvance($user);

        if ($response->status) {
            return ok('Usuario registrado correctamente', $response);
        }

        throw new \Error('No se pudo crear el avance de rango');
    }

    public function completePreregister($data, $user)
    {
        $user->username = $data['username'];
        $user->password = bcrypt($data['password']);
        $user->is_active = 1;
        $profile = Profile::create([
            'name' => $data['name'],
            'lastname' => $data['lastname'],
            'email' => $data['email'],
            'profession' => $data['profession'] ?? null,
        ]);
        $user->profile_id = $profile->id;
        $user->save();

        return $user;
    }

    public function completeRegister($data)
    {
        $profile = Profile::create([
            'name' => $data['name'],
            'lastname' => $data['lastname'],
            'email' => $data['email'],
            'profession' => $data['profession'] ?? null,
        ]);

        $user = User::create([
            'username' => $data['username'],
            'email' => $data['email'],
            'password' => bcrypt($data['password']),
            'is_active' => 1,
            'profile_id' => $profile->id,
        ])->fresh();
        $user->assignRole('usuario');

        return $user;
    }

    public function checkPassword($data)
    {
        $user = Auth::user();

        if (Hash::check($data['password'], $user->password)) {
            return ok('Contraseña correcta');
        } else {
            return bad_request('Contraseña incorrecta');
        }
    }
}
