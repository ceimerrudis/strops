<?php

namespace App\Http\Middleware;

use Closure;
use app\Enums\UserTypes;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class IsAdminMiddleware
{
    /*
    Šī pārbaude nodrošina ka lietotājs ir administrators 
    */
    public function handle(Request $request, Closure $next): Response
    {
        if($request->user() && $request->user()->type == UserTypes::ADMIN)
            return $next($request);
        
        return response()->view('unauthorized', [], 403);
    }
}
