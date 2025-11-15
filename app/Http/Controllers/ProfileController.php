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

    public function edit(): \Illuminate\View\View
    {
        $guard = $this->getGuard();
        /** @var \App\Models\Admin|\App\Models\Guru|\App\Models\OrangTua $user */
        $user = auth($guard)->user();

        return view('profile.edit', compact('user', 'guard'));
    }

    public function update(Request $request): \Illuminate\Http\RedirectResponse
    {
        $guard = $this->getGuard();
        /** @var \App\Models\Admin|\App\Models\Guru|\App\Models\OrangTua $user */
        $user = auth($guard)->user();

        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . $user->id,
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

        return view('profile.change-password', compact('guard'));
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

        if (!Hash::check($validated['current_password'], $user->password)) {
            return back()->withErrors(['current_password' => 'Password saat ini tidak sesuai.']);
        }

        $user->password = Hash::make($validated['password']);
        $user->save();

        return redirect()->route('profile.password')->with('success', 'Password berhasil diubah.');
    }
}
