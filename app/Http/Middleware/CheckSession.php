<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckSession
{
    public function handle(Request $request, Closure $next)
    {
        if (!session()->has('auth_token')) {
            return redirect('/login')->with('error', 'Silakan login dulu.');
        }

        return $next($request);
    }
}

