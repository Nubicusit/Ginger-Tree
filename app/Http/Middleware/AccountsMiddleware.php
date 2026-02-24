<?php
namespace App\Http\Middleware;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AccountsMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $user = Auth::user();

        // Admin-നും Accountant-നും access
        if ($user->role === 'admin' || $user->role === 'accountant') {
            return $next($request);
        }

        abort(403, 'Unauthorized');
    }
}