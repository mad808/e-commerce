<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class AdminMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        if (auth()->check() && (auth()->user()->role === 'admin' || auth()->user()->role === 'operator')) {
            return $next($request);
        }

        // If not authorized, send them to the front-end home page
        return redirect('/')->with('error', 'Sizde bu bölüme girmäge hukuk ýok.');
    }
}
