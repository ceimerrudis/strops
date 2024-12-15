<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AuthenticationMiddleware
{
    /*
    Šī pārbaude nodrošina ka lietotājs ir pieteicies sistēmā
    */
    public function handle(Request $request, Closure $next): Response
    {
        if($request->user())
            return $next($request);
    }
}
