@extends('layouts.karyawan')

@section('content')
    <div class="max-w-4xl mx-auto space-y-6">
        <!-- HEADER PAGE -->
        <div>
            <h1 class="text-2xl font-bold text-slate-800 tracking-tight">Pengaturan Akun Saya</h1>
            <p class="text-xs text-slate-500 mt-1">Kelola informasi kontak pribadi dan amankan kata sandi akun Anda.</p>
        </div>

        <!-- STAT CARDS / INFO AKUN KARYAWAN -->
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
            <div class="bg-white p-4 rounded-2xl border border-slate-100 shadow-sm flex items-center space-x-3.5">
                <div
                    class="w-11 h-11 rounded-xl bg-orange-50 text-[#FF6B00] flex items-center justify-center font-bold text-base shrink-0">
                    <i class="fa-solid fa-id-card"></i>
                </div>
                <div>
                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">NIK Karyawan</p>
                    <h4 class="text-sm font-extrabold text-slate-800 tracking-tight">{{ $employee->nik ?? '-' }}</h4>
                </div>
            </div>

            <div class="bg-white p-4 rounded-2xl border border-slate-100 shadow-sm flex items-center space-x-3.5">
                <div
                    class="w-11 h-11 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center font-bold text-base shrink-0">
                    <i class="fa-solid fa-briefcase"></i>
                </div>
                <div class="overflow-hidden">
                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Jabatan / Posisi</p>
                    <h4 class="text-xs font-extrabold text-slate-800 tracking-tight truncate">
                        {{ $employee->position->name ?? 'Karyawan' }}
                    </h4>
                </div>
            </div>

            <div class="bg-white p-4 rounded-2xl border border-slate-100 shadow-sm flex items-center space-x-3.5">
                <div
                    class="w-11 h-11 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center font-bold text-base shrink-0">
                    <i class="fa-solid fa-location-dot"></i>
                </div>
                <div class="overflow-hidden">
                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Lokasi Penempatan</p>
                    <h4 class="text-xs font-extrabold text-slate-800 tracking-tight truncate">
                        {{ $employee->location->name ?? '-' }}
                    </h4>
                </div>
            </div>
        </div>

        <!-- SECTION 1: PROFIL KARYAWAN -->
        <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
            <div class="p-5 border-b border-slate-100 bg-slate-50/50 flex items-center justify-between">
                <div class="flex items-center space-x-3">
                    <div
                        class="w-8 h-8 rounded-lg bg-orange-50 text-[#FF6B00] flex items-center justify-center font-bold text-xs shrink-0">
                        <i class="fa-solid fa-user-pen"></i>
                    </div>
                    <div>
                        <h2 class="text-sm font-bold text-slate-800">Informasi Profil</h2>
                        <p class="text-[11px] text-slate-400">Data resmi terikat ke HRD. Anda hanya dapat memperbarui Nomor
                            HP/WhatsApp.</p>
                    </div>
                </div>
            </div>

            <div class="p-6">
                @if (session('success_profile'))
                    <div
                        class="mb-5 p-4 bg-emerald-50 border border-emerald-200 text-emerald-700 text-xs font-semibold rounded-2xl flex items-center space-x-2 shadow-sm">
                        <i class="fa-solid fa-circle-check text-base text-emerald-600"></i>
                        <span>{{ session('success_profile') }}</span>
                    </div>
                @endif

                @if (session('error_profile'))
                    <div
                        class="mb-5 p-4 bg-rose-50 border border-rose-200 text-rose-700 text-xs font-semibold rounded-2xl flex items-center space-x-2 shadow-sm">
                        <i class="fa-solid fa-circle-exclamation text-base text-rose-600"></i>
                        <span>{{ session('error_profile') }}</span>
                    </div>
                @endif

                <form action="{{ route('employee.settings.updatePhone') }}" method="POST" class="space-y-4">
                    @csrf
                    @method('PUT')

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <!-- Nama Lengkap (Disabled) -->
                        <div>
                            <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-1">
                                Nama Lengkap
                            </label>
                            <div class="relative">
                                <input type="text" value="{{ $user->name }}" disabled
                                    class="w-full pl-9 pr-3.5 py-2.5 rounded-xl border border-slate-100 bg-slate-100 text-slate-500 text-xs cursor-not-allowed">
                                <i class="fa-solid fa-user absolute left-3 top-3 text-slate-400 text-xs"></i>
                            </div>
                        </div>

                        <!-- Email Login (Disabled) -->
                        <div>
                            <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-1">
                                Email Login
                            </label>
                            <div class="relative">
                                <input type="email" value="{{ $user->email }}" disabled
                                    class="w-full pl-9 pr-3.5 py-2.5 rounded-xl border border-slate-100 bg-slate-100 text-slate-500 text-xs cursor-not-allowed">
                                <i class="fa-solid fa-envelope absolute left-3 top-3 text-slate-400 text-xs"></i>
                            </div>
                        </div>
                    </div>

                    <!-- Nomor HP / WhatsApp (Bisa di-edit) -->
                    <div class="pt-2">
                        <label class="block text-xs font-bold text-slate-600 uppercase tracking-wider mb-1">
                            Nomor Handphone / WhatsApp <span class="text-rose-500">*</span>
                        </label>
                        <div class="relative">
                            <input type="text" name="phone" value="{{ old('phone', $employee->phone ?? '') }}" required
                                placeholder="Contoh: 081234567890"
                                class="w-full pl-9 pr-3.5 py-2.5 rounded-xl border border-slate-200 text-xs focus:outline-none focus:border-[#FF6B00] @error('phone') border-rose-500 @enderror">
                            <i class="fa-brands fa-whatsapp absolute left-3 top-3 text-slate-400 text-xs"></i>
                        </div>
                        @error('phone')
                            <p class="text-rose-500 text-[10px] mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="pt-4 flex justify-end border-t border-slate-100 mt-6">
                        <button type="submit"
                            class="px-5 py-2.5 bg-slate-800 hover:bg-slate-900 text-white font-bold rounded-xl text-xs transition shadow-md flex items-center space-x-2">
                            <i class="fa-solid fa-floppy-disk"></i>
                            <span>Simpan Nomor HP</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- SECTION 2: UBAH KATA SANDI -->
        <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
            <div class="p-5 border-b border-slate-100 bg-slate-50/50 flex items-center justify-between">
                <div class="flex items-center space-x-3">
                    <div
                        class="w-8 h-8 rounded-lg bg-orange-50 text-[#FF6B00] flex items-center justify-center font-bold text-xs shrink-0">
                        <i class="fa-solid fa-key"></i>
                    </div>
                    <div>
                        <h2 class="text-sm font-bold text-slate-800">Ubah Kata Sandi</h2>
                        <p class="text-[11px] text-slate-400">Pastikan menggunakan kombinasi kata sandi yang kuat dan mudah
                            Anda ingat.</p>
                    </div>
                </div>
            </div>

            <div class="p-6">
                @if (session('success_password'))
                    <div
                        class="mb-5 p-4 bg-emerald-50 border border-emerald-200 text-emerald-700 text-xs font-semibold rounded-2xl flex items-center space-x-2 shadow-sm">
                        <i class="fa-solid fa-circle-check text-base text-emerald-600"></i>
                        <span>{{ session('success_password') }}</span>
                    </div>
                @endif

                <form action="{{ route('employee.settings.updatePassword') }}" method="POST" class="space-y-4">
                    @csrf
                    @method('PUT')

                    <div>
                        <label class="block text-xs font-bold text-slate-600 uppercase tracking-wider mb-1">
                            Password Saat Ini <span class="text-rose-500">*</span>
                        </label>
                        <div class="relative">
                            <input type="password" name="current_password" required
                                placeholder="Masukkan password lama Anda"
                                class="w-full pl-9 pr-3.5 py-2.5 rounded-xl border border-slate-200 text-xs focus:outline-none focus:border-[#FF6B00] @error('current_password') border-rose-500 @enderror">
                            <i class="fa-solid fa-lock absolute left-3 top-3 text-slate-400 text-xs"></i>
                        </div>
                        @error('current_password')
                            <p class="text-rose-500 text-[10px] font-semibold mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-bold text-slate-600 uppercase tracking-wider mb-1">
                                Password Baru <span class="text-rose-500">*</span>
                            </label>
                            <div class="relative">
                                <input type="password" name="password" required placeholder="Minimal 6 karakter"
                                    class="w-full pl-9 pr-3.5 py-2.5 rounded-xl border border-slate-200 text-xs focus:outline-none focus:border-[#FF6B00] @error('password') border-rose-500 @enderror">
                                <i class="fa-solid fa-shield-halved absolute left-3 top-3 text-slate-400 text-xs"></i>
                            </div>
                            @error('password')
                                <p class="text-rose-500 text-[10px] font-semibold mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-600 uppercase tracking-wider mb-1">
                                Konfirmasi Password Baru <span class="text-rose-500">*</span>
                            </label>
                            <div class="relative">
                                <input type="password" name="password_confirmation" required
                                    placeholder="Ulangi password baru"
                                    class="w-full pl-9 pr-3.5 py-2.5 rounded-xl border border-slate-200 text-xs focus:outline-none focus:border-[#FF6B00]">
                                <i class="fa-solid fa-check-double absolute left-3 top-3 text-slate-400 text-xs"></i>
                            </div>
                        </div>
                    </div>

                    <div class="pt-4 flex justify-end border-t border-slate-100 mt-6">
                        <button type="submit"
                            class="px-5 py-2.5 bg-[#FF6B00] hover:bg-[#e66000] text-white font-bold text-xs rounded-xl shadow-lg shadow-orange-500/20 transition flex items-center space-x-2">
                            <i class="fa-solid fa-arrows-rotate"></i>
                            <span>Update Password</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
