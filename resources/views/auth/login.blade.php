<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - GB PARKING Payroll System</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap');

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
        }

        .text-orange-gb {
            color: #FF5A1F;
        }

        .bg-wave-pattern {
            background: radial-gradient(circle at 10% 20%, rgba(255, 90, 31, 0.05) 0%, transparent 40%),
                radial-gradient(circle at 90% 80%, rgba(255, 90, 31, 0.03) 0%, transparent 40%);
        }
    </style>
</head>

<body
    class="bg-slate-50 min-h-screen flex flex-col justify-between antialiased selection:bg-orange-500 selection:text-white bg-wave-pattern">

    <!-- MAIN CONTAINER -->
    <div class="max-w-7xl w-full mx-auto p-6 md:p-12 flex-1 flex flex-col justify-center">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-center">

            <!-- KIRI: BRANDING & DESKRIPSI -->
            <div class="lg:col-span-6 space-y-8 pr-0 lg:pr-8">

                <!-- LOGO GB PARKING -->
                <div class="flex items-center space-x-3">
                    <div
                        class="w-12 h-12 bg-gradient-to-br from-[#FF6B00] to-[#FF4500] rounded-2xl flex items-center justify-center font-extrabold text-white text-2xl shadow-lg shadow-orange-500/30">
                        GB
                    </div>
                    <div>
                        <h1 class="text-2xl font-black text-slate-900 tracking-tight leading-none">PARKING</h1>
                        <p class="text-[10px] text-orange-gb font-bold tracking-widest uppercase mt-0.5">• PARKING
                            MANAGEMENT</p>
                    </div>
                </div>

                <!-- BADGE & TITLE -->
                <div class="space-y-4">
                    <span
                        class="inline-block px-3.5 py-1.5 bg-orange-50 border border-orange-200 text-orange-gb font-bold text-xs rounded-full">
                        GB PAYROLL SYSTEM
                    </span>

                    <h2 class="text-4xl lg:text-5xl font-extrabold text-slate-900 leading-[1.15]">
                        Sistem Payroll Terpadu<br>
                        <span class="text-orange-gb">GB Parking</span>
                    </h2>

                    <p class="text-slate-500 text-sm md:text-base leading-relaxed max-w-lg">
                        Kelola data karyawan, perhitungan gaji, potongan, hingga laporan payroll dalam satu sistem
                        terintegrasi dan akurat.
                    </p>
                </div>

                <!-- FEATURE ICONS (4 ICON) -->
                <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 pt-4">
                    <!-- Icon 1 -->
                    <div class="text-center space-y-2">
                        <div
                            class="w-12 h-12 mx-auto rounded-full bg-orange-50/80 border border-orange-100 flex items-center justify-center text-orange-gb">
                            <i class="fa-solid fa-shield text-lg"></i>
                        </div>
                        <h4 class="text-xs font-bold text-slate-800">Aman & Terpercaya</h4>
                        <p class="text-[10px] text-slate-400">Keamanan data terjamin</p>
                    </div>

                    <!-- Icon 2 -->
                    <div class="text-center space-y-2">
                        <div
                            class="w-12 h-12 mx-auto rounded-full bg-orange-50/80 border border-orange-100 flex items-center justify-center text-orange-gb">
                            <i class="fa-solid fa-chart-pie text-lg"></i>
                        </div>
                        <h4 class="text-xs font-bold text-slate-800">Akurat & Real-time</h4>
                        <p class="text-[10px] text-slate-400">Perhitungan cepat dan akurat</p>
                    </div>

                    <!-- Icon 3 -->
                    <div class="text-center space-y-2">
                        <div
                            class="w-12 h-12 mx-auto rounded-full bg-orange-50/80 border border-orange-100 flex items-center justify-center text-orange-gb">
                            <i class="fa-solid fa-file-lines text-lg"></i>
                        </div>
                        <h4 class="text-xs font-bold text-slate-800">Laporan Lengkap</h4>
                        <p class="text-[10px] text-slate-400">Berbagai laporan siap digunakan</p>
                    </div>

                    <!-- Icon 4 -->
                    <div class="text-center space-y-2">
                        <div
                            class="w-12 h-12 mx-auto rounded-full bg-orange-50/80 border border-orange-100 flex items-center justify-center text-orange-gb">
                            <i class="fa-solid fa-users-gear text-lg"></i>
                        </div>
                        <h4 class="text-xs font-bold text-slate-800">Akses Berbasis Role</h4>
                        <p class="text-[10px] text-slate-400">Hak akses sesuai peran pengguna</p>
                    </div>
                </div>

            </div>

            <!-- KANAN: CARD FORM LOGIN -->
            <div class="lg:col-span-6 flex justify-center lg:justify-end">
                <div
                    class="w-full max-w-md bg-white rounded-3xl border border-slate-100 shadow-xl shadow-slate-200/50 p-8 md:p-10 space-y-6">

                    <!-- HEADER FORM -->
                    <div class="text-center space-y-3">
                        <div
                            class="w-16 h-16 mx-auto bg-orange-50/80 rounded-full border border-orange-100 p-2 flex items-center justify-center shadow-inner">
                            <div
                                class="w-full h-full bg-white rounded-full flex flex-col items-center justify-center shadow-sm">
                                <span class="text-xs font-black text-orange-gb leading-none">GB</span>
                                <span
                                    class="text-[7px] font-bold text-slate-700 leading-none tracking-tighter mt-0.5">PAYROLL</span>
                            </div>
                        </div>

                        <div>
                            <h3 class="text-2xl font-bold text-slate-900">Selamat Datang!</h3>
                            <p class="text-xs text-slate-400 mt-1">Masuk untuk mengakses sistem payroll GB Parking</p>
                        </div>
                    </div>

                    <!-- NOTIFIKASI ERROR -->
                    @if ($errors->any())
                        <div class="p-3 bg-rose-50 border border-rose-200 rounded-2xl text-xs text-rose-600 space-y-1">
                            @foreach ($errors->all() as $error)
                                <p><i class="fa-solid fa-circle-exclamation mr-1"></i> {{ $error }}</p>
                            @endforeach
                        </div>
                    @endif

                    <!-- FORM UTAMA -->
                    <form method="POST" action="{{ route('login') }}" class="space-y-4">
                        @csrf

                        <!-- INPUT EMAIL / USERNAME -->
                        <div class="space-y-1.5">
                            <label class="block text-xs font-bold text-slate-700">E-Mail / Username</label>
                            <div class="relative">
                                <span
                                    class="absolute inset-y-0 left-0 flex items-center pl-3.5 pointer-events-none text-slate-400">
                                    <i class="fa-regular fa-envelope text-sm"></i>
                                </span>
                                <input type="email" name="email" value="{{ old('email') }}" required autofocus
                                    placeholder="Masukkan email atau username Anda"
                                    class="w-full pl-10 pr-4 py-3 bg-slate-50/50 border border-slate-200 rounded-2xl text-xs focus:outline-none focus:border-orange-gb focus:bg-white transition">
                            </div>
                        </div>

                        <!-- INPUT PASSWORD -->
                        <div class="space-y-1.5">
                            <div class="flex items-center justify-between">
                                <label class="block text-xs font-bold text-slate-700">Password</label>
                                @if (Route::has('password.request'))
                                    <a href="{{ route('password.request') }}"
                                        class="text-xs font-semibold text-orange-gb hover:underline">Lupa Password?</a>
                                @else
                                    <a href="#"
                                        onclick="alert('Fitur Reset Password belum diaktifkan oleh Admin.')"
                                        class="text-xs font-semibold text-orange-gb hover:underline">Lupa Password?</a>
                                @endif
                            </div>
                            <div class="relative">
                                <span
                                    class="absolute inset-y-0 left-0 flex items-center pl-3.5 pointer-events-none text-slate-400">
                                    <i class="fa-solid fa-lock text-sm"></i>
                                </span>
                                <input type="password" id="passwordInput" name="password" required
                                    placeholder="Masukkan password Anda"
                                    class="w-full pl-10 pr-10 py-3 bg-slate-50/50 border border-slate-200 rounded-2xl text-xs focus:outline-none focus:border-orange-gb focus:bg-white transition">
                                <button type="button" onclick="togglePassword()"
                                    class="absolute inset-y-0 right-0 pr-3.5 flex items-center text-slate-400 hover:text-slate-600">
                                    <i class="fa-regular fa-eye text-sm" id="toggleIcon"></i>
                                </button>
                            </div>
                        </div>

                        <!-- CHECKBOX INGAT SAYA -->
                        <div class="flex items-center justify-between pt-1">
                            <label class="flex items-center space-x-2 cursor-pointer">
                                <input type="checkbox" name="remember"
                                    class="w-4 h-4 text-orange-gb border-slate-300 rounded focus:ring-orange-gb">
                                <span class="text-xs font-medium text-slate-600">Ingat saya</span>
                            </label>
                        </div>

                        <!-- BUTTON MASUK -->
                        <div class="pt-2">
                            <button type="submit"
                                class="w-full py-3.5 px-4 bg-gradient-to-r from-[#FF6B00] to-[#FF4500] hover:from-[#e66000] hover:to-[#e03d00] text-white font-bold text-xs rounded-2xl shadow-lg shadow-orange-500/25 flex items-center justify-center space-x-2 transition transform active:scale-[0.99]">
                                <i class="fa-solid fa-arrow-right text-xs"></i>
                                <span>Masuk</span>
                            </button>
                        </div>

                    </form>
                </div>
            </div>

        </div>
    </div>

    <!-- FOOTER LOGIN -->
    <footer
        class="max-w-7xl w-full mx-auto px-6 py-6 flex flex-col md:flex-row items-center justify-between text-xs text-slate-400 gap-4 border-t border-slate-200/60 mt-6">
        <p>© {{ date('Y') }} GB Parking – Payroll System. All rights reserved.</p>

        <div class="flex items-center space-x-6">
            <a href="#" class="hover:text-slate-600 transition">Kebijakan Privasi</a>
            <span>|</span>
            <a href="#" class="hover:text-slate-600 transition">Ketentuan Layanan</a>
            <span>|</span>
            <a href="#" class="hover:text-slate-600 transition">Bantuan</a>

            <span
                class="px-2.5 py-1 bg-orange-50 border border-orange-200 text-orange-gb font-bold rounded-full text-[10px] ml-2">
                v1.0
            </span>
        </div>
    </footer>

    <!-- SCRIPT EYE TOGGLE PASSWORD -->
    <script>
        function togglePassword() {
            const input = document.getElementById('passwordInput');
            const icon = document.getElementById('toggleIcon');

            if (input.type === 'password') {
                input.type = 'text';
                icon.classList.remove('fa-eye');
                icon.classList.add('fa-eye-slash');
            } else {
                input.type = 'password';
                icon.classList.remove('fa-eye-slash');
                icon.classList.add('fa-eye');
            }
        }
    </script>
</body>

</html>
