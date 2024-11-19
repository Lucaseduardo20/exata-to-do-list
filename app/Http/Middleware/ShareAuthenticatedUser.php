<?php

namespace App\Http\Middleware;

use App\Data\UserData;
use Closure;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ShareAuthenticatedUser
{
    public function handle(Request $request, Closure $next)
    {
        Inertia::share('auth.user', function () use ($request) {
            return $request->user()
                ? UserData::from($request->user())
                : null;
        });

        return $next($request);
    }
}
