<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EstimateMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $user = Auth::user();
        $slug = $user->department?->slug;

        if ($user->role === 'admin' || $slug === 'estimator') {
            return $next($request);
        }

        abort(403, 'Unauthorized');
    }
}
