<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as Middleware;

class VerifyCsrfToken extends Middleware
{
    /**
     * The URIs that should be excluded from CSRF verification.
     *
     * @var array<int, string>
     */
    protected $except = [
        // Busca pública de distribuidores (somente leitura). O formulário atual usa GET;
        // o POST permanece apenas por compatibilidade com páginas já abertas.
        'busca',
    ];
}
