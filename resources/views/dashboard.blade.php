<x-app-layout>
    <x-slot name="title">Dashboard - GB PARKING PAYROLL</x-slot>

    <div class="space-y-6">

        <!-- 1. HEADER DASHBOARD -->
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
            <div>
                <h1 class="text-2xl font-black text-slate-800 tracking-tight">Dashboard Payroll</h1>
                <p class="text-xs text-slate-500 mt-1">
                    Selamat datang kembali, <span
                        class="font-bold text-slate-700">{{ Auth::user()->name ?? 'Super Admin' }}</span> 👋
                </p>
            </div>

            <!-- Filter Tahun Dropdown -->
            <div
                class="inline-flex items-center space-x-2 bg-white px-3.5 py-2 rounded-xl border border-slate-200 shadow-sm text-xs font-semibold text-slate-600 self-start sm:self-auto">
                <i class="fa-regular fa-calendar text-[#FF6B00]"></i>
                <span>{{ date('d F Y') }}</span>
            </div>
        </div>

        <!-- 2. 4 KOTAK UTAMA (STATISTIK DINAMIS) -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">

            <!-- Card 1: Total Karyawan -->
            <div class="bg-white p-5 rounded-2xl border border-slate-100 shadow-sm flex items-center justify-between">
                <div>
                    <p class="text-xs font-bold text-slate-400 uppercase tracking-wider">Total Karyawan</p>
                    <h3 class="text-2xl font-black text-slate-800 mt-1">{{ $totalKaryawan ?? 0 }}</h3>
                    <p class="text-[11px] text-emerald-600 font-semibold mt-1">
                        <i class="fa-solid fa-user-check"></i> Aktif Bekerja
                    </p>
                </div>
                <div
                    class="w-12 h-12 rounded-2xl bg-orange-50 text-[#FF6B00] flex items-center justify-center text-xl font-bold">
                    <i class="fa-solid fa-users"></i>
                </div>
            </div>

            <!-- Card 2: Total Lokasi / Cabang -->
            <div class="bg-white p-5 rounded-2xl border border-slate-100 shadow-sm flex items-center justify-between">
                <div>
                    <p class="text-xs font-bold text-slate-400 uppercase tracking-wider">Lokasi / Area</p>
                    <h3 class="text-2xl font-black text-slate-800 mt-1">{{ $totalLokasi ?? 0 }}</h3>
                    <p class="text-[11px] text-slate-400 font-medium mt-1">Area GB Parking</p>
                </div>
                <div
                    class="w-12 h-12 rounded-2xl bg-blue-50 text-blue-600 flex items-center justify-center text-xl font-bold">
                    <i class="fa-solid fa-location-dot"></i>
                </div>
            </div>

            <!-- Card 3: Total Estimasi Payroll -->
            <div class="bg-white p-5 rounded-2xl border border-slate-100 shadow-sm flex items-center justify-between">
                <div>
                    <p class="text-xs font-bold text-slate-400 uppercase tracking-wider">Estimasi Payroll</p>
                    <h3 class="text-xl font-black text-slate-800 mt-1">
                        Rp {{ number_format($totalPayrollBulanIni ?? 0, 0, ',', '.') }}
                    </h3>
                    <p class="text-[11px] text-slate-400 font-medium mt-1">Periode {{ date('F Y') }}</p>
                </div>
                <div
                    class="w-12 h-12 rounded-2xl bg-emerald-50 text-emerald-600 flex items-center justify-center text-xl font-bold">
                    <i class="fa-solid fa-wallet"></i>
                </div>
            </div>

            <!-- Card 4: Status Approval Payroll -->
            <div class="bg-white p-5 rounded-2xl border border-slate-100 shadow-sm flex items-center justify-between">
                <div>
                    <p class="text-xs font-bold text-slate-400 uppercase tracking-wider">Approval Payroll</p>
                    <h3 class="text-2xl font-black text-slate-800 mt-1">{{ $pendingApproval ?? 0 }}</h3>
                    <p class="text-[11px] text-amber-600 font-semibold mt-1">Menunggu Persetujuan</p>
                </div>
                <div
                    class="w-12 h-12 rounded-2xl bg-amber-50 text-amber-600 flex items-center justify-center text-xl font-bold">
                    <i class="fa-solid fa-file-signature"></i>
                </div>
            </div>

        </div>

        <!-- 3. SECTION GRAFIK & AKSES CEPAT -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

            <!-- GRAFIK BATANG DINAMIS -->
            <div
                class="lg:col-span-2 bg-white rounded-2xl p-6 border border-slate-100 shadow-sm flex flex-col justify-between">
                <div class="flex items-center justify-between mb-4">
                    <div>
                        <h3 class="text-sm font-bold text-slate-800">Total Pengeluaran Payroll</h3>
                        <p class="text-[11px] text-slate-400 mt-0.5">Grafik pengeluaran bulanan</p>
                    </div>

                    <!-- Filter Tahun Khusus Diagram -->
                    <form method="GET" action="{{ route('dashboard') }}"
                        class="flex items-center space-x-1.5 bg-slate-50 px-2.5 py-1 rounded-lg border border-slate-200">
                        <i class="fa-solid fa-filter text-[#FF6B00] text-[10px]"></i>
                        <select name="year" onchange="this.form.submit()"
                            class="text-[11px] font-bold text-slate-700 bg-transparent border-none focus:outline-none cursor-pointer">
                            @for ($y = date('Y'); $y >= date('Y') - 4; $y--)
                                <option value="{{ $y }}"
                                    {{ request('year', date('Y')) == $y ? 'selected' : '' }}>
                                    Tahun {{ $y }}
                                </option>
                            @endfor
                        </select>
                    </form>
                </div>

                <!-- Canvas Chart (ID SUDAH DISATAKAN) -->
                <div class="h-56 relative w-full">
                    <canvas id="payrollChart"></canvas>
                </div>
            </div>

            <!-- AKSES CEPAT MENU -->
            <div
                class="bg-white rounded-2xl p-6 border border-slate-100 shadow-sm flex flex-col justify-between space-y-4">
                <div>
                    <h3 class="text-sm font-bold text-slate-800">Akses Cepat</h3>
                    <p class="text-[11px] text-slate-400 mt-0.5">Menu utama pengoperasian</p>
                </div>

                <div class="space-y-3">
                    <a href="{{ route('payrolls.create') }}"
                        class="p-3 rounded-xl border border-slate-100 hover:border-orange-200 hover:bg-orange-50/40 transition flex items-center space-x-3 group">
                        <div
                            class="w-9 h-9 rounded-lg bg-orange-100 text-[#FF6B00] group-hover:bg-[#FF6B00] group-hover:text-white flex items-center justify-center transition text-sm">
                            <i class="fa-solid fa-calculator"></i>
                        </div>
                        <div>
                            <p class="text-xs font-bold text-slate-800">Hitung Gaji Bulan Ini</p>
                            <p class="text-[10px] text-slate-400">Proses kalkulasi payroll</p>
                        </div>
                    </a>

                    <a href="{{ route('employees.create') }}"
                        class="p-3 rounded-xl border border-slate-100 hover:border-orange-200 hover:bg-orange-50/40 transition flex items-center space-x-3 group">
                        <div
                            class="w-9 h-9 rounded-lg bg-orange-100 text-[#FF6B00] group-hover:bg-[#FF6B00] group-hover:text-white flex items-center justify-center transition text-sm">
                            <i class="fa-solid fa-user-plus"></i>
                        </div>
                        <div>
                            <p class="text-xs font-bold text-slate-800">Tambah Karyawan</p>
                            <p class="text-[10px] text-slate-400">Input data personil baru</p>
                        </div>
                    </a>

                    <a href="{{ route('reports.payroll') }}"
                        class="p-3 rounded-xl border border-slate-100 hover:border-orange-200 hover:bg-orange-50/40 transition flex items-center space-x-3 group">
                        <div
                            class="w-9 h-9 rounded-lg bg-orange-100 text-[#FF6B00] group-hover:bg-[#FF6B00] group-hover:text-white flex items-center justify-center transition text-sm">
                            <i class="fa-solid fa-file-invoice-dollar"></i>
                        </div>
                        <div>
                            <p class="text-xs font-bold text-slate-800">Laporan Payroll</p>
                            <p class="text-[10px] text-slate-400">Unduh ringkasan & rekapan</p>
                        </div>
                    </a>
                </div>

                <div class="pt-2 border-t border-slate-50 text-center">
                    <span class="text-[11px] text-slate-400 font-medium">Sistem Operational GB Parking v1.0</span>
                </div>
            </div>

        </div>

    </div>

    <!-- SCRIPT SINGLE CHART.JS -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const canvasElem = document.getElementById('payrollChart');
            if (!canvasElem) return;

            const ctx = canvasElem.getContext('2d');

            const chartLabels = {!! json_encode($chartLabels) !!};
            const chartData = {!! json_encode($chartData) !!};

            new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: chartLabels,
                    datasets: [{
                        label: 'Total Pengeluaran',
                        data: chartData,
                        backgroundColor: '#FF6B00',
                        borderRadius: 6,
                        barThickness: 24,
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: false
                        },
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    let value = context.raw || 0;
                                    return ' Total: Rp ' + new Intl.NumberFormat('id-ID').format(value);
                                }
                            }
                        }
                    },
                    scales: {
                        x: {
                            grid: {
                                display: false
                            }
                        },
                        y: {
                            beginAtZero: true,
                            // Batas minimum tampilan skala sumbu Y (misal min 50 Juta sebagai patokan awal)
                            suggestedMax: 50000000,
                            ticks: {
                                callback: function(value) {
                                    if (value === 0) return '0';
                                    if (value >= 1000000) {
                                        return (value / 1000000) +
                                        ' Jt'; // Otomatis tampil 10 Jt, 20 Jt, 50 Jt, 100 Jt, dst.
                                    }
                                    return value;
                                }
                            }
                        }
                    }
                }
            });
        });
    </script>
</x-app-layout>
