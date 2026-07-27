<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class EmployeeSettingController extends Controller
{
    public function index()
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        // Ambil data karyawan beserta relasi position dan location-nya
        $employee = \App\Models\Employee::with(['position', 'location'])
            ->where('user_id', $user->id)
            ->first();

        return view('employee.settings.index', compact('user', 'employee'));
    }

    public function updatePhone(Request $request)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        $employee = $user->employee;

        if (!$employee) {
            return redirect()->back()->with('error_profile', 'Data profil karyawan tidak ditemukan!');
        }

        $request->validate([
            'phone' => 'required|string|max:20',
        ]);

        $employee->update([
            'phone' => $request->phone,
        ]);

        return redirect()->back()->with('success_profile', 'Nomor telepon/WhatsApp berhasil diperbarui!');
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'password'         => 'required|min:6|confirmed',
        ]);

        /** @var \App\Models\User $user */
        $user = Auth::user();

        // Validasi password lama
        if (!Hash::check($request->current_password, $user->password)) {
            return redirect()->back()->withErrors(['current_password' => 'Password lama Anda tidak cocok!']);
        }

        // Update password baru
        $user->update([
            'password' => $request->password,
        ]);

        return redirect()->back()->with('success_password', 'Password akun Anda berhasil diperbarui!');
    }
}
