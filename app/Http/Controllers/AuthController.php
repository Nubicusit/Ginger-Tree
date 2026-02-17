<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class AuthController extends Controller
{
    public function showLogin()
    {
        return view('welcome');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        $user = User::with('department')->where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return back()->withErrors(['email' => 'Invalid email or password.'])
                ->onlyInput('email');
        }

        Auth::login($user, $request->filled('remember'));

        // Admin by role
        if ($user->role === 'admin') {
            return redirect()->route('admin.dashboard');
        }

        // Everyone else redirected by department slug
        $slug = $user->department?->slug;
        $routeName = $slug . '.dashboard';

        if ($slug && \Route::has($routeName)) {
            return redirect()->route($routeName);
        }

        return redirect('/'); // Default fallback
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    }
}
