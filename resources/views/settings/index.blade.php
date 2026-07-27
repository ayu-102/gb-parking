<x-app-layout>
    <div class="max-w-4xl mx-auto space-y-6">
        <!-- HEADER PAGE -->
        <div>
            <h1 class="text-2xl font-bold text-slate-800 tracking-tight">Pengaturan Akun & Keamanan</h1>
            <p class="text-xs text-slate-500 mt-1">Kelola informasi profil administrator dan perbarui kata sandi sistem
                GB Parking.</p>
        </div>

        <!-- STAT CARDS / INFO AKUN -->
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
            <div class="bg-white p-4 rounded-2xl border border-slate-100 shadow-sm flex items-center space-x-3.5">
                <div
                    class="w-11 h-11 rounded-xl bg-orange-50 text-[#FF6B00] flex items-center justify-center font-bold text-base shrink-0">
                    <i class="fa-solid fa-user-shield"></i>
                </div>
                <div>
                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Hak Akses</p>
                    <h4 class="text-sm font-extrabold text-slate-800 tracking-tight">Administrator</h4>
                </div>
            </div>

            <div class="bg-white p-4 rounded-2xl border border-slate-100 shadow-sm flex items-center space-x-3.5">
                <div
                    class="w-11 h-11 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center font-bold text-base shrink-0">
                    <i class="fa-solid fa-envelope"></i>
                </div>
                <div class="overflow-hidden">
                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Email Login</p>
                    <h4 class="text-xs font-extrabold text-slate-800 tracking-tight truncate"
                        title="{{ auth()->user()->email }}">
                        {{ auth()->user()->email }}
                    </h4>
                </div>
            </div>

            <div class="bg-white p-4 rounded-2xl border border-slate-100 shadow-sm flex items-center space-x-3.5">
                <div
                    class="w-11 h-11 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center font-bold text-base shrink-0">
                    <i class="fa-solid fa-shield"></i>
                </div>
                <div>
                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Status Keamanan</p>
                    <h4 class="text-xs font-extrabold text-emerald-600 tracking-tight flex items-center gap-1.5 mt-0.5">
                        <i class="fa-solid fa-circle-check text-emerald-500 text-xs"></i>
                        <span>Terproteksi</span>
                    </h4>
                </div>
            </div>
        </div>

        <!-- SECTION 1: PROFIL ADMIN -->
        <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
            <div class="p-5 border-b border-slate-100 bg-slate-50/50 flex items-center justify-between">
                <div class="flex items-center space-x-3">
                    <div
                        class="w-8 h-8 rounded-lg bg-orange-50 text-[#FF6B00] flex items-center justify-center font-bold text-xs shrink-0">
                        <i class="fa-solid fa-user-gear"></i>
                    </div>
                    <div>
                        <h2 class="text-sm font-bold text-slate-800">Profil Akun Admin</h2>
                        <p class="text-[11px] text-slate-400">Perbarui nama dan alamat email login akun Anda.</p>
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

                <form action="{{ route('settings.updateProfile') }}" method="POST" class="space-y-4">
                    @csrf
                    @method('PUT')

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-bold text-slate-600 uppercase tracking-wider mb-1">
                                Nama Lengkap <span class="text-rose-500">*</span>
                            </label>
                            <div class="relative">
                                <input type="text" name="name" value="{{ old('name', auth()->user()->name) }}"
                                    required
                                    class="w-full pl-9 pr-3.5 py-2.5 rounded-xl border border-slate-200 text-xs focus:outline-none focus:border-[#FF6B00] @error('name') border-rose-500 @enderror">
                                <i class="fa-solid fa-user absolute left-3 top-3 text-slate-400 text-xs"></i>
                            </div>
                            @error('name')
                                <p class="text-rose-500 text-[10px] mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-600 uppercase tracking-wider mb-1">
                                Email Login <span class="text-rose-500">*</span>
                            </label>
                            <div class="relative">
                                <input type="email" name="email" value="{{ old('email', auth()->user()->email) }}"
                                    required
                                    class="w-full pl-9 pr-3.5 py-2.5 rounded-xl border border-slate-200 text-xs focus:outline-none focus:border-[#FF6B00] @error('email') border-rose-500 @enderror">
                                <i class="fa-solid fa-envelope absolute left-3 top-3 text-slate-400 text-xs"></i>
                            </div>
                            @error('email')
                                <p class="text-rose-500 text-[10px] mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="pt-4 flex justify-end border-t border-slate-100 mt-6">
                        <button type="submit"
                            class="px-5 py-2.5 bg-slate-800 hover:bg-slate-900 text-white font-bold rounded-xl text-xs transition shadow-md flex items-center space-x-2">
                            <i class="fa-solid fa-floppy-disk"></i>
                            <span>Simpan Profil</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- SECTION 2: KEAMANAN / GANTI PASSWORD -->
        <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
            <div class="p-5 border-b border-slate-100 bg-slate-50/50 flex items-center justify-between">
                <div class="flex items-center space-x-3">
                    <div
                        class="w-8 h-8 rounded-lg bg-orange-50 text-[#FF6B00] flex items-center justify-center font-bold text-xs shrink-0">
                        <i class="fa-solid fa-key"></i>
                    </div>
                    <div>
                        <h2 class="text-sm font-bold text-slate-800">Ubah Kata Sandi</h2>
                        <p class="text-[11px] text-slate-400">Pastikan menggunakan kombinasi kata sandi yang kuat dan
                            aman.</p>
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

                <form action="{{ route('settings.updatePassword') }}" method="POST" class="space-y-4">
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
</x-app-layout>
