<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as Middleware;

class VerifyCsrfToken extends Middleware
{
    /**
     * URIs exemptées de la vérification CSRF.
     *
     * @var array<int, string>
     */
    protected $except = [
        '/auth', // Exclure toutes les routes API
        '/verify-token'
    ];
}
