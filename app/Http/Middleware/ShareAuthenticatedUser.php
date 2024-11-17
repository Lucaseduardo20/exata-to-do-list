<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ShareAuthenticatedUser
{
    public function handle(Request $request, Closure $next)
    {
        Inertia::share('auth.user', function () use ($request) {
            return $request->user()
                ? [
                    'id' => $request->user()->id,
                    'name' => $request->user()->name,
                    'email' => $request->user()->email,
                    'role' => $request->user()->role,
                ]
                : null;
        });

        return $next($request);
    }
}
