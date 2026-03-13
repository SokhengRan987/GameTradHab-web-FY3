<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
         if (!auth()->check()) {
        return redirect()->route('login');
        }
        if (!auth()->user()->isAdmin()) {
        return redirect()->route('home')
            ->with('error', 'You do not have admin access.');
    }

        return $next($request);
    }
}
