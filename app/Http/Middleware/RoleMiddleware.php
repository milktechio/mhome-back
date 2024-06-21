<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next, $roleGroup)
    {
        $roles = auth()->user()->roles;

        foreach ($roles as $role) {
            if (str_contains($roleGroup, $role->name)) {
                return $next($request);
            }
        }

        return forbidden('Rol no valido', []);
    }
}
