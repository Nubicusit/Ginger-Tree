<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class MarketingController extends Controller
{
   public function store(Request $request)
{
    $request->validate([
        'type' => 'required|in:marketing,designer',
        'email' => 'required|email|unique:users,email',
        'password' => 'required|string|min:6',
        'contact_no' => 'nullable|string|max:20',
    ]);

    $name = $request->type === 'marketing'
        ? $request->marketing_name
        : $request->designer_name;

    if (!$name) {
        return response()->json([
            'success' => false,
            'message' => 'Name is required'
        ], 422);
    }

    $user = User::create([
        'name'       => $name,
        'email'      => $request->email,
        'contact_no' => $request->contact_no,
        'password'   => Hash::make($request->password),
        'role'       => $request->type === 'marketing'
                            ? 'sales_executive'
                            : 'designer',
        'status'     => 1,
    ]);

    return response()->json([
        'success' => true,
        'user' => [
            'id'    => $user->id,
            'name'  => $user->name,
            'email' => $user->email,
            'role'  => $user->role,
        ]
    ]);
}


}
