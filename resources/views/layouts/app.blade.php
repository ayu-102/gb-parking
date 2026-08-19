<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'GB PARKING - Payroll System' }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap');

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: #F8FAFC;
        }

        .bg-gb-orange {
            background-color: #FF6B00;
        }

        .text-gb-orange {
            color: #FF6B00;
        }

        .border-gb-orange {
            border-color: #FF6B00;
        }
    </style>
</head>

<body class="bg-slate-50 text-slate-800 antialiased overflow-hidden">

    <!-- WRAPPER UTAMA (Kunci layar biar gak scroll global) -->
    <div class="h-screen flex overflow-hidden">

        <!-- SIDEBAR (Memiliki tinggi layar penuh & scroll tersendiri) -->
        <aside class="w-64 bg-white border-r border-slate-200 flex flex-col justify-between shrink-0 h-screen">
            <!-- HEADER LOGO -->
            <div class="p-5 border-b border-slate-100 flex items-center space-x-3 shrink-0">
                <div
                    class="w-10 h-10 bg-gb-orange rounded-xl flex items-center justify-center font-bold text-white text-xl shadow-lg shadow-orange-500/30">
                    GB
                </div>
                <div>
                    <h1 class="font-bold text-slate-900 leading-tight">GB PARKING</h1>
                    <p class="text-[10px] text-slate-400 font-semibold tracking-wider uppercase">PAYROLL SYSTEM</p>
                </div>
            </div>

            <!-- NAVIGATION MENU (Bisa di-scroll tersendiri) -->
            <nav class="p-4 space-y-6 text-xs font-semibold overflow-y-auto flex-1">
                <!-- MENU UTAMA -->
                <div>
                    <p class="text-[10px] text-slate-400 uppercase tracking-wider mb-2 font-bold px-3">Menu Utama
                    </p>
                    <a href="{{ route('dashboard') }}"
                        class="flex items-center space-x-3 px-3 py-2.5 rounded-lg transition {{ request()->routeIs('dashboard') ? 'bg-orange-50 text-gb-orange font-bold' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900' }}">
                        <i class="fa-solid fa-chart-pie w-4 text-center"></i>
                        <span>Dashboard</span>
                    </a>
                </div>

                <!-- MASTER DATA -->
                <div>
                    <p class="text-[10px] text-slate-400 uppercase tracking-wider mb-2 font-bold px-3">Master Data
                    </p>
                    <div class="space-y-1">
                        <a href="{{ route('employees.index') }}"
                            class="flex items-center space-x-3 px-3 py-2 rounded-lg transition {{ request()->routeIs('employees.*') ? 'bg-orange-50 text-gb-orange font-bold' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900' }}">
                            <i class="fa-solid fa-users w-4 text-center"></i>
                            <span>Karyawan</span>
                        </a>
                        <a href="{{ route('positions.index') }}"
                            class="flex items-center space-x-3 px-3.5 py-2.5 rounded-xl text-xs transition duration-200 {{ request()->routeIs('positions.*') ? 'bg-orange-50 text-[#FF6B00] font-bold shadow-sm' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900 font-medium' }}">
                            <i
                                class="fa-solid fa-briefcase w-4 text-center text-sm {{ request()->routeIs('positions.*') ? 'text-[#FF6B00]' : 'text-slate-400' }}"></i>
                            <span>Jabatan</span>
                        </a>
                        <a href="{{ route('departments.index') }}"
                            class="flex items-center space-x-3 px-3 py-2 rounded-lg transition {{ request()->routeIs('departments.*') ? 'bg-orange-50 text-gb-orange font-bold' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900' }}">
                            <i class="fa-solid fa-building w-4 text-center"></i>
                            <span>Departemen</span>
                        </a>
                        <a href="{{ route('locations.index') }}"
                            class="flex items-center space-x-3 px-3 py-2 rounded-lg transition {{ request()->routeIs('locations.*') ? 'bg-orange-50 text-gb-orange font-bold' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900' }}">
                            <i class="fa-solid fa-location-dot w-4 text-center"></i>
                            <span>Lokasi</span>
                        </a>
                        <a href="{{ route('salary-components.index') }}"
                            class="flex items-center space-x-3 px-3 py-2 rounded-lg transition {{ request()->routeIs('salary-components.*') ? 'bg-orange-50 text-gb-orange font-bold' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900' }}">
                            <i class="fa-solid fa-wallet w-4 text-center"></i>
                            <span>Komponen Gaji</span>
                        </a>
                        <a href="{{ route('deductions.index') }}"
                            class="flex items-center space-x-3 px-3 py-2 rounded-lg transition {{ request()->routeIs('deductions.*') ? 'bg-orange-50 text-gb-orange font-bold' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900' }}">
                            <i class="fa-solid fa-scissors w-4 text-center"></i>
                            <span>Potongan</span>
                        </a>
                    </div>
                </div>

                <!-- AKTIVITAS -->
                <div>
                    <p class="text-[10px] text-slate-400 uppercase tracking-wider mb-2 font-bold px-3">Aktivitas</p>
                    <div class="space-y-1 text-slate-600">
                        <a href="{{ route('attendances.index') }}"
                            class="flex items-center space-x-3 px-3 py-2 rounded-lg transition {{ request()->routeIs('attendances.*') ? 'bg-orange-50 text-gb-orange font-bold' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900' }}">
                            <i class="fa-solid fa-calendar-check w-4 text-center"></i>
                            <span>Absensi</span>
                        </a>
                        <a href="{{ route('leaves.index') }}"
                            class="flex items-center space-x-3 px-3 py-2 rounded-lg transition {{ request()->routeIs('leaves.*') ? 'bg-orange-50 text-gb-orange font-bold' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900' }}">
                            <i class="fa-solid fa-file-signature w-4 text-center"></i>
                            <span>Pengajuan Cuti & Izin</span>
                        </a>
                        <a href="{{ route('employee-shifts.index') }}"
                            class="flex items-center space-x-3 px-3 py-2 rounded-lg transition {{ request()->routeIs('employee-shifts.*') ? 'bg-orange-50 text-gb-orange font-bold' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900' }}">
                            <i class="fa-solid fa-calendar-days w-4 text-center"></i>
                            <span>Jadwal Shift</span>
                        </a>
                        <a href="{{ route('bonuses.index') }}"
                            class="flex items-center space-x-3 px-3 py-2 rounded-lg transition {{ request()->routeIs('bonuses.*') ? 'bg-orange-50 text-gb-orange font-bold' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900' }}">
                            <i class="fa-solid fa-gift w-4 text-center"></i>
                            <span>Bonus & Insentif</span>
                        </a>
                    </div>
                </div>

                <!-- PAYROLL -->
                <div>
                    <p class="text-[10px] text-slate-400 uppercase tracking-wider mb-2 font-bold px-3">Payroll</p>
                    <div class="space-y-1 text-slate-600">
                        <a href="{{ route('payrolls.index') }}"
                            class="flex items-center space-x-3 px-3 py-2 rounded-lg transition {{ request()->routeIs('payrolls.index', 'payrolls.create', 'payrolls.edit') ? 'bg-orange-50 text-gb-orange font-bold' : 'text-slate-600 hover:bg-slate-50' }}">
                            <i class="fa-solid fa-calculator w-4 text-center"></i>
                            <span>Proses Payroll</span>
                        </a>
                        <a href="{{ route('payrolls.approval') }}"
                            class="flex items-center space-x-3 px-3 py-2 rounded-lg transition {{ request()->routeIs('payrolls.approval') ? 'bg-orange-50 text-gb-orange font-bold' : 'text-slate-600 hover:bg-slate-50' }}">
                            <i class="fa-solid fa-circle-check w-4 text-center"></i>
                            <span>Approval Payroll</span>
                        </a>
                        <a href="{{ route('payrolls.slip') }}"
                            class="flex items-center space-x-3 px-3 py-2 rounded-lg transition {{ request()->routeIs('payrolls.slip') ? 'bg-orange-50 text-gb-orange font-bold' : 'text-slate-600 hover:bg-slate-50' }}">
                            <i class="fa-solid fa-receipt w-4 text-center"></i>
                            <span>Slip Gaji</span>
                        </a>
                    </div>
                </div>

                <!-- Laporan -->
                <div>
                    <p class="text-[10px] text-slate-400 uppercase tracking-wider mb-2 font-bold px-3">Laporan
                    </p>
                    <div class="space-y-1 text-slate-600">
                        <!-- Laporan Payroll -->
                        <a href="{{ route('reports.payroll') }}"
                            class="flex items-center space-x-3 px-3 py-2 rounded-lg transition {{ request()->routeIs('reports.payroll') ? 'bg-orange-50 text-gb-orange font-bold' : 'text-slate-600 hover:bg-slate-50' }}">
                            <i class="fa-solid fa-file-invoice-dollar w-4 text-center"></i>
                            <span>Laporan Payroll</span>
                        </a>

                        <!-- Laporan Pajak -->
                        <a href="{{ route('reports.tax') }}"
                            class="flex items-center space-x-3 px-3 py-2 rounded-lg transition {{ request()->routeIs('reports.tax') ? 'bg-orange-50 text-gb-orange font-bold' : 'text-slate-600 hover:bg-slate-50' }}">
                            <i class="fa-solid fa-file-contract w-4 text-center"></i>
                            <span>Laporan Pajak</span>
                        </a>

                        <!-- Laporan BPJS -->
                        <a href="{{ route('reports.bpjs') }}"
                            class="flex items-center space-x-3 px-3 py-2 rounded-lg transition {{ request()->routeIs('reports.bpjs') ? 'bg-orange-50 text-gb-orange font-bold' : 'text-slate-600 hover:bg-slate-50' }}">
                            <i class="fa-solid fa-notes-medical w-4 text-center"></i>
                            <span>Laporan BPJS</span>
                        </a>
                    </div>
                </div>

                <!-- Settings -->
                <div>
                    <p class="text-[10px] text-slate-400 uppercase tracking-wider mb-2 font-bold px-3">Pengaturan
                    </p>
                    <div class="space-y-1 text-slate-600">
                        <a href="{{ route('settings.index') }}"
                            class="flex items-center space-x-3 px-3 py-2 rounded-lg transition {{ request()->routeIs('settings.*') ? 'bg-orange-50 text-[#FF6B00] font-bold' : 'text-slate-600 hover:bg-slate-50' }}">
                            <i class="fa-solid fa-gear w-4 text-center"></i>
                            <span>Pengaturan</span>
                        </a>
                    </div>
                </div>

            </nav>


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
        </aside>

        <!-- MAIN CONTENT AREA (Bisa di-scroll mandiri) -->
        <div class="flex-1 flex flex-col min-w-0 h-screen overflow-hidden">
            <!-- TOP NAVBAR -->
            <header class="h-16 bg-white border-b border-slate-200 flex items-center justify-between px-6 shrink-0">
                <div class="flex items-center space-x-4">
                    <button class="text-slate-400 hover:text-slate-600"><i
                            class="fa-solid fa-bars text-lg"></i></button>
                    <span class="text-xs text-slate-400 font-medium capitalize">
                        {{ request()->route()->getName() ? str_replace(['.', 'index', 'create', 'edit'], [' / ', '', '', ''], request()->route()->getName()) : 'Dashboard' }}
                    </span>
                </div>

                <div class="flex items-center space-x-4">
                    <div class="flex items-center space-x-3 border-l border-slate-200 pl-4">
                        <div class="w-9 h-9 rounded-full bg-slate-200 flex items-center justify-center overflow-hidden">
                            <img src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name ?? 'Admin') }}&background=FF6B00&color=fff"
                                alt="User Avatar">
                        </div>
                        <div>
                            <p class="text-xs font-bold text-slate-800">{{ Auth::user()->name ?? 'Super Admin' }}</p>
                            <p class="text-[10px] text-slate-400">{{ Auth::user()->email ?? 'admin@gbparking.com' }}
                            </p>
                        </div>

                        <form method="POST" action="{{ route('logout') }}" class="ml-2">
                            @csrf
                            <button type="submit" title="Logout"
                                class="p-2 text-slate-400 hover:text-red-500 rounded-lg hover:bg-red-50 transition">
                                <i class="fa-solid fa-right-from-bracket"></i>
                            </button>
                        </form>
                    </div>
                </div>
            </header>

            <!-- MAIN BODY (Area Scroll Konten Dashboard) -->
            <main class="flex-1 overflow-y-auto p-6">
                @yield('content', $slot ?? '')
            </main>
        </div>
    </div>

</body>

</html>
