<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DepartmentMiddleware
{
    public function handle(Request $request, Closure $next, string $slug)
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $userSlug = Auth::user()->department?->slug;

        if ($userSlug !== $slug) {
            abort(403, 'Unauthorized');
        }

        return $next($request);
    }
}
