<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Http\Request;

class Authenticate extends Middleware
{
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     */
    protected function redirectTo(Request $request): ?string
    {
        if ($request->expectsJson()) {
            return null;
        }

        // Se a URL começa com /admin, redireciona para login do admin
        if ($request->is('admin/*') || $request->is('admin')) {
            return route('admin.login');
        }

        // Caso contrário, redireciona para login do distribuidor
        return route('distributor.login');
    }
}
