<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckAdmin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Verifica se o usuário está autenticado usando o guard padrão (users)
        if (!auth()->check()) {
            return redirect()->route('admin.login');
        }

        // Verifica se o usuário é administrador
        if (!auth()->user()->is_admin) {
            auth()->logout();
            return redirect()->route('admin.login')->with('error', 'Acesso não autorizado.');
        }

        return $next($request);
    }
}
