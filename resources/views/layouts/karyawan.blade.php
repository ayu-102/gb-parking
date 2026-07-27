<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? 'Dashboard Karyawan - GB PARKING' }}</title>

    <!-- Tailwind CSS & FontAwesome -->
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
        }

        /* Fix Un-mirror Kamera */
        #my_camera video {
            transform: scaleX(1) !important;
            -webkit-transform: scaleX(1) !important;
        }
    </style>
</head>

<body class="bg-slate-50 text-slate-800 font-sans antialiased">

    <div class="flex min-h-screen">
        <!-- SIDEBAR KARYAWAN -->
        <aside class="w-64 bg-white border-r border-slate-100 flex flex-col justify-between p-5 fixed h-full z-30">
            <div>
                <!-- LOGO GB PARKING -->
                <div class="flex items-center space-x-3 px-2 mb-8">
                    <div
                        class="w-10 h-10 rounded-xl bg-[#FF6B00] flex items-center justify-center text-white font-black text-xl shadow-lg shadow-orange-500/30">
                        GB
                    </div>
                    <div>
                        <h1 class="font-extrabold text-sm text-slate-800 tracking-tight leading-none">GB PARKING</h1>
                        <p class="text-[10px] text-slate-400 font-semibold tracking-widest uppercase mt-1">PAYROLL
                            SYSTEM</p>
                    </div>
                </div>

                <!-- NAVIGATION MENU -->
                <nav class="space-y-1.5">
                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider px-3 mb-2">MENU KARYAWAN</p>

                    <a href="{{ route('presence.index') }}"
                        class="flex items-center space-x-3 px-3.5 py-2.5 rounded-xl font-bold text-xs transition {{ request()->routeIs('presence.index') ? 'bg-orange-50 text-[#FF6B00]' : 'text-slate-500 hover:bg-slate-50' }}">
                        <i class="fa-solid fa-house text-sm"></i>
                        <span>Dashboard Saya</span>
                    </a>

                    <a href="{{ route('presence.live_gps') }}"
                        class="flex items-center space-x-3 px-3.5 py-2.5 rounded-xl font-bold text-xs transition {{ request()->routeIs('presence.live_gps') ? 'bg-orange-50 text-[#FF6B00]' : 'text-slate-500 hover:bg-slate-50' }}">
                        <i class="fa-solid fa-location-crosshairs text-sm text-[#FF6B00]"></i>
                        <span>Absensi Live GPS</span>
                    </a>

                    <a href="{{ route('employee.leaves.index') }}"
                        class="flex items-center space-x-3 px-3.5 py-2.5 rounded-xl font-bold text-xs transition {{ request()->routeIs('employee.leaves.*') ? 'bg-orange-50 text-[#FF6B00]' : 'text-slate-500 hover:bg-slate-50' }}">
                        <i class="fa-solid fa-file-signature text-sm text-[#FF6B00]"></i>
                        <span>Pengajuan Cuti / Izin</span>
                    </a>

                    <a href="{{ route('presence.payrolls') }}"
                        class="flex items-center space-x-3 px-3.5 py-2.5 rounded-xl font-bold text-xs transition {{ request()->routeIs('presence.payrolls') ? 'bg-orange-50 text-[#FF6B00]' : 'text-slate-500 hover:bg-slate-50' }}">
                        <i class="fa-solid fa-file-invoice-dollar text-sm"></i>
                        <span>Slip Gaji Saya</span>
                    </a>

                    <a href="{{ route('employee.settings.index') }}"
                        class="flex items-center space-x-3 px-3.5 py-2.5 rounded-xl font-bold text-xs transition {{ request()->routeIs('employee.settings.*') ? 'bg-orange-50 text-[#FF6B00]' : 'text-slate-500 hover:bg-slate-50' }}">
                        <i class="fa-solid fa-gear text-sm"></i>
                        <span>Pengaturan Akun</span>
                    </a>
                </nav>
            </div>

            <!-- FOOTER SIDEBAR -->
            <a href="https://wa.me/6285124157382?text=Halo%20Admin%20GB%20Parking,%20saya%20butuh%20bantuan%20mengenai%20sistem%20presensi."
                target="_blank"
                class="bg-orange-50 hover:bg-orange-100/80 rounded-2xl p-4 border border-orange-100 transition flex items-center space-x-3 group cursor-pointer block">
                <div
                    class="w-8 h-8 rounded-lg bg-emerald-500 text-white flex items-center justify-center font-bold text-sm shadow-md shadow-emerald-500/20 group-hover:scale-110 transition">
                    <i class="fa-brands fa-whatsapp"></i>
                </div>
                <div>
                    <p class="text-xs font-bold text-slate-800 group-hover:text-[#FF6B00] transition">Butuh Bantuan?</p>
                    <p class="text-[10px] text-slate-500 font-medium">Chat Support via WhatsApp &rarr;</p>
                </div>
            </a>
        </aside> <!-- PENUTUP ASIDE HARUS DI SINI -->

        <!-- MAIN CONTENT CONTAINER -->
        <div class="flex-1 ml-64 min-h-screen flex flex-col">
            <!-- TOPBAR -->
            <header
                class="h-16 bg-white/80 backdrop-blur-md border-b border-slate-100 px-8 flex items-center justify-between sticky top-0 z-20">
                <div class="flex items-center space-x-2 text-xs font-bold text-slate-400">
                    <i class="fa-solid fa-bars mr-2 text-slate-600"></i>
                    <span>Karyawan</span>
                    <span>/</span>
                    <span class="text-slate-800">Dashboard</span>
                </div>

                <!-- USER PROFILE HEADER -->
                <div class="flex items-center space-x-4">
                    <div class="flex items-center space-x-3">
                        <div
                            class="w-9 h-9 rounded-full bg-[#FF6B00] text-white font-bold flex items-center justify-center text-xs shadow-md shadow-orange-500/20">
                            {{ strtoupper(substr(Auth::user()->name ?? 'Karyawan', 0, 2)) }}
                        </div>
                        <div class="text-left">
                            <h4 class="text-xs font-bold text-slate-800 leading-tight">
                                {{ Auth::user()->name ?? 'Karyawan' }}</h4>
                            <p class="text-[10px] text-slate-400 font-medium">{{ Auth::user()->email ?? '' }}</p>
                        </div>
                    </div>

                    <!-- LOGOUT BUTTON -->
                    <form method="POST" action="{{ route('logout') }}" class="inline">
                        @csrf
                        <button type="submit" class="p-2 text-slate-400 hover:text-rose-600 transition" title="Logout">
                            <i class="fa-solid fa-right-from-bracket text-sm"></i>
                        </button>
                    </form>
                </div>
            </header>

            <!-- PAGE CONTENT -->
            <main class="p-8 flex-1">
                @yield('content')
            </main>
        </div>
    </div>

    @stack('scripts')
</body>

</html>
