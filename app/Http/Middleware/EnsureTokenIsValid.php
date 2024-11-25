<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;


class EnsureTokenIsValid
{
    public function handle($request, Closure $next)
    {
        if (!session('api_token')) {
            return redirect('/')->withErrors(['error' => 'You must log in first.']);
        }

        return $next($request);
    }
}

?>