<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function show()
    {
        return view('auth.login');
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
        ]);

        $credentials = $request->only('email', 'password');

        if (!Auth::attempt(
            $credentials + ['is_active' => true],
            $request->boolean('remember')
        )) {
            return back()
                ->withErrors([
                    'email' => 'Invalid credentials or inactive account.',
                ])
                ->onlyInput('email');
        }

        $request->session()->regenerate();

        $user = Auth::user();

        // Only allow accounts that have an assigned role.
        if (!$user->role_id || !$user->role) {
            Auth::logout();

            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return back()
                ->withErrors([
                    'email' => 'No role is assigned to this account. Contact the administrator.',
                ])
                ->onlyInput('email');
        }

        // Record the successful login.
        $user->forceFill([
            'last_login_at' => now(),
        ])->save();

    if (in_array($user->role->slug, ['super-admin', 'admin'], true)) {
         return redirect('/students');
  } 

       return redirect()->route('dashboard');
    }

    public function destroy(Request $request): RedirectResponse
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login');
    }
}