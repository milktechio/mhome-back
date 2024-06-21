<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Auth;
use App\Models\User;

class UserMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        $jwt = JWT::decode($request->bearerToken(), new Key(env('JWT_SECRET'), 'HS256'));
        // dd($jwt->sub);
        // $user = $request->user;
        if (isset($jwt->sub)) {
            $user = User::parse($jwt->sub);
            if (!$user) {
                return bad_request('No se encontro el usuario');
            }
            $request->user = $user;
            Auth::login($user);
        } else {

            return unauthorized('Se requiere iniciar sesion');
        }
        return $next($request);
    }
}
