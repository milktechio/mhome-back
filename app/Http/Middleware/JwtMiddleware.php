<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class JwtMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        $request->jwt = json_decode($request->header('jwt'), true);

        \Config::set('jwt', $request->jwt);
        \Config::set('token', $request->bearerToken());

        if (isset($request->jwt['sub'])) {
            $user = User::parse($request->jwt['sub']);
            if (! $user) {
                return bad_request('No se encontro el usuario');
            }
            $request->user = $user;
            Auth::login($user);
        }

        return $next($request);
    }
}
