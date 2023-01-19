<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use Hash;

class EnsurePasswordIsChanged
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $usuario = Auth::user();

        if (isset($usuario)) {
            if (Hash::check(env('DEFAULT_PASSWORD', ''), $usuario->password)) {
                return redirect('/alterasenha')->with("error", "Altere a sua senha para obter acesso ao sistema.");
            }
        }

        return $next($request);
    }
}
