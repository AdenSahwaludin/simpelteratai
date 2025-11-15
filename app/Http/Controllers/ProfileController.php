<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{
    private function getGuard(): string
    {
        if (auth('admin')->check()) {
            return 'admin';
        } elseif (auth('guru')->check()) {
            return 'guru';
        } elseif (auth('orangtua')->check()) {
            return 'orangtua';
        }

        return 'web';
    }

    private function getProfileData(string $guard, $user): array
    {
        return [
            'user' => $user,
            'guard' => $guard,
            'navColor' => match ($guard) {
                'admin' => 'bg-blue-600',
                'guru' => 'bg-green-600',
                'orangtua' => 'bg-purple-600',
                default => 'bg-gray-600',
            },
            'role' => match ($guard) {
                'admin' => 'Admin',
                'guru' => 'Guru',
                'orangtua' => 'Orang Tua',
                default => 'User',
            },
            'iconColor' => match ($guard) {
                'admin' => '#2563eb',
                'guru' => '#16a34a',
                'orangtua' => '#a855f7',
                default => '#6b7280',
            },
            'dashboardRoute' => match ($guard) {
                'admin' => 'admin.dashboard',
                'guru' => 'guru.dashboard',
                'orangtua' => 'orangtua.dashboard',
                default => 'dashboard',
            },
        ];
    }

    public function edit(): \Illuminate\View\View
    {
        $guard = $this->getGuard();
        /** @var \App\Models\Admin|\App\Models\Guru|\App\Models\OrangTua $user */
        $user = auth($guard)->user();

        $data = $this->getProfileData($guard, $user);

        return view('profile.edit', $data);
    }

    public function update(Request $request): \Illuminate\Http\RedirectResponse
    {
        $guard = $this->getGuard();
        /** @var \App\Models\Admin|\App\Models\Guru|\App\Models\OrangTua $user */
        $user = auth($guard)->user();

        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,'.$user->id,
            'no_telpon' => 'nullable|string|max:20',
        ]);

        $user->nama = $validated['nama'];
        $user->email = $validated['email'];
        $user->no_telpon = $validated['no_telpon'] ?? null;
        $user->save();

        return redirect()->route('profile.edit')->with('success', 'Profil berhasil diperbarui.');
    }

    public function passwordForm(): \Illuminate\View\View
    {
        $guard = $this->getGuard();
        /** @var \App\Models\Admin|\App\Models\Guru|\App\Models\OrangTua $user */
        $user = auth($guard)->user();

        $data = $this->getProfileData($guard, $user);

        return view('profile.change-password', $data);
    }

    public function updatePassword(Request $request): \Illuminate\Http\RedirectResponse
    {
        $guard = $this->getGuard();
        /** @var \App\Models\Admin|\App\Models\Guru|\App\Models\OrangTua $user */
        $user = auth($guard)->user();

        $validated = $request->validate([
            'current_password' => 'required',
            'password' => 'required|string|min:8|confirmed',
        ]);

        if (! Hash::check($validated['current_password'], $user->password)) {
            return back()->withErrors(['current_password' => 'Password saat ini tidak sesuai.']);
        }

        $user->password = Hash::make($validated['password']);
        $user->save();

        return redirect()->route('profile.password')->with('success', 'Password berhasil diubah.');
    }
}
