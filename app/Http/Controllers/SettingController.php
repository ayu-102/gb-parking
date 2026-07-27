<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class SettingController extends Controller
{
    public function index()
    {
        return view('settings.index');
    }

    public function updateProfile(Request $request)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        $request->validate([
            'name'  => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
        ]);

        $user->update([
            'name'  => $request->name,
            'email' => $request->email,
        ]);

        return redirect()->back()->with('success_profile', 'Profil admin berhasil diperbarui!');
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password'  => 'required',
            'password'          => 'required|min:6|confirmed',
        ]);

        /** @var \App\Models\User $user */
        $user = Auth::user();


        if (!Hash::check($request->current_password, $user->password)) {
            return redirect()->back()->withErrors(['current_password' => 'Password lama kamu salah!']);
        }


        $user->update([
            'password' => Hash::make($request->password),
        ]);

        return redirect()->back()->with('success_password', 'Password berhasil diubah!');
    }
}
