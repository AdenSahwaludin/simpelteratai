<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use App\Models\Guru;
use App\Models\OrangTua;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class LoginController extends Controller
{
    /**
     * Show the login form.
     */
    public function showLoginForm(): View|RedirectResponse
    {
        // Check if user is already logged in
        if (Auth::guard('admin')->check()) {
            return redirect('/admin/dashboard');
        }

        if (Auth::guard('guru')->check()) {
            return redirect('/guru/dashboard');
        }

        if (Auth::guard('orangtua')->check()) {
            return redirect('/orangtua/dashboard');
        }

        return view('auth.login');
    }

    /**
     * Handle login attempt for all user roles.
     */
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'login' => 'required|string',
            'password' => 'required|string',
        ]);

        // Try to login as Admin with email
        if (Auth::guard('admin')->attempt(['email' => $credentials['login'], 'password' => $credentials['password']])) {
            $request->session()->regenerate();

            return redirect('/admin/dashboard');
        }

        // Try to login as Guru with email or NIP
        if (
            Auth::guard('guru')->attempt(['email' => $credentials['login'], 'password' => $credentials['password']]) ||
            Auth::guard('guru')->attempt(['nip' => $credentials['login'], 'password' => $credentials['password']])
        ) {
            $request->session()->regenerate();

            return redirect('/guru/dashboard');
        }

        // Try to login as OrangTua with email
        if (Auth::guard('orangtua')->attempt(['email' => $credentials['login'], 'password' => $credentials['password']])) {
            $request->session()->regenerate();

            return redirect('/orangtua/dashboard');
        }

        // If all failed, redirect back with error
        return back()
            ->withInput($request->only('login'))
            ->withErrors([
                'login' => 'Email/NIP atau password salah.',
            ]);
    }

    /**
     * Handle logout.
     */
    public function logout(Request $request)
    {
        Auth::guard('admin')->logout();
        Auth::guard('guru')->logout();
        Auth::guard('orangtua')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}
