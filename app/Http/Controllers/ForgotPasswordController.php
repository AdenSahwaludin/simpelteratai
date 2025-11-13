<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use App\Models\Guru;
use App\Models\OrangTua;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\View\View;

class ForgotPasswordController extends Controller
{
 /**
  * Show the forgot password form.
  */
 public function show(): View
 {
  return view('auth.forgot-password');
 }

 /**
  * Handle forgot password request.
  */
 public function store(Request $request)
 {
  $request->validate([
   'email' => 'required|email',
  ]);

  $email = $request->email;

  // Check which user type has this email
  $admin = Admin::where('email', $email)->first();
  $guru = Guru::where('email', $email)->first();
  $orangTua = OrangTua::where('email', $email)->first();

  if (!$admin && !$guru && !$orangTua) {
   return back()->withErrors([
    'email' => 'Email tidak ditemukan.',
   ]);
  }

  // Generate reset token
  $token = Str::random(64);

  // Store token in cache for 60 minutes
  $cacheKey = 'password_reset_' . $email;
  cache()->put($cacheKey, [
   'token' => $token,
   'user_type' => $admin ? 'admin' : ($guru ? 'guru' : 'orangtua'),
  ], now()->addMinutes(60));

  // In production, you would send an email here
  // For now, we'll generate a reset URL for testing
  $resetUrl = route('password.reset', ['token' => $token, 'email' => $email]);

  return back()->with('status', 'Link reset password telah dikirim ke email Anda. Klik link berikut: ' . $resetUrl);
 }

 /**
  * Show the reset password form.
  */
 public function showResetForm(Request $request, $token): View
 {
  $email = $request->email;

  // Verify token exists
  $cacheKey = 'password_reset_' . $email;
  $resetData = cache()->get($cacheKey);

  if (!$resetData || $resetData['token'] !== $token) {
   abort(403, 'Token tidak valid atau sudah kadaluarsa.');
  }

  return view('auth.reset-password', [
   'token' => $token,
   'email' => $email,
  ]);
 }

 /**
  * Handle password reset.
  */
 public function reset(Request $request)
 {
  $request->validate([
   'email' => 'required|email',
   'password' => 'required|string|min:6|confirmed',
   'token' => 'required',
  ]);

  $email = $request->email;
  $token = $request->token;

  // Verify token
  $cacheKey = 'password_reset_' . $email;
  $resetData = cache()->get($cacheKey);

  if (!$resetData || $resetData['token'] !== $token) {
   return back()->withErrors([
    'token' => 'Token tidak valid atau sudah kadaluarsa.',
   ]);
  }

  // Find user and reset password
  $userType = $resetData['user_type'];

  if ($userType === 'admin') {
   $user = Admin::where('email', $email)->first();
  } elseif ($userType === 'guru') {
   $user = Guru::where('email', $email)->first();
  } else {
   $user = OrangTua::where('email', $email)->first();
  }

  if (!$user) {
   return back()->withErrors([
    'email' => 'Email tidak ditemukan.',
   ]);
  }

  // Update password
  $user->password = $request->password;
  $user->save();

  // Clear reset token
  cache()->forget($cacheKey);

  return redirect()->route('login')->with('status', 'Password berhasil direset. Silakan login dengan password baru Anda.');
 }
}
