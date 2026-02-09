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

        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return back()->withErrors(['email' => 'Invalid email or password.'])
                ->onlyInput('email');
        }

        if ($user->status != 1) {
            return back()->withErrors(['email' => 'Your account is inactive.'])
                ->onlyInput('email');
        }

        if (!Hash::check($request->password, $user->password)) {
            return back()->withErrors(['email' => 'Invalid email or password.'])
                ->onlyInput('email');
        }

        Auth::login($user, $request->filled('remember'));

        // ===== HR REDIRECT ADDED =====
        if ($user->role === 'admin') {
            return redirect()->route('admin.dashboard');
        }

        if ($user->role === 'hr') {  // ← ADD THIS
            return redirect()->route('hr.dashboard');
        }

        if ($user->role === 'sales_executive') {
            return redirect()->route('sales.dashboard');
        }

        return redirect('/');  // Default fallback
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    }
}
