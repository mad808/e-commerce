<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; // Add this line

class CheckBlocked
{
    public function handle(Request $request, Closure $next)
    {
        // Using the Facade solves the VS Code red lines
        if (Auth::check() && Auth::user()->status === 'blocked') {
            Auth::logout();

            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return redirect()->route('login')->with('error', 'Your account has been suspended.');
        }

        return $next($request);
    }
}
