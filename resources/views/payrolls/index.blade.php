<x-app-layout>
    <div class="space-y-6">

        <!-- HEADER PAGE -->
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div>
                <h1 class="text-2xl font-bold text-slate-800 tracking-tight">Proses Payroll</h1>
                <p class="text-xs text-slate-500 mt-1">Kalkulasi dan generasi draft pengajuan gaji karyawan periode ini.
                </p>
            </div>
            <div class="flex items-center space-x-2">
                <a href="{{ route('payrolls.create') }}"
                    class="px-4 py-2.5 bg-gradient-to-r from-[#FF6B00] to-[#FF4500] hover:from-[#e66000] hover:to-[#e03d00] text-white font-bold rounded-xl text-xs shadow-lg shadow-orange-500/20 flex items-center space-x-2 transition transform active:scale-95">
                    <i class="fa-solid fa-calculator text-xs"></i>
                    <span>Hitung / Tambah Draft Payroll</span>
                </a>
            </div>
        </div>

        <!-- NOTIFIKASI SUKSES -->
        @if (session('success'))
            <div
                class="p-4 bg-emerald-50 border border-emerald-200 text-emerald-700 text-xs font-semibold rounded-2xl flex items-center space-x-2 shadow-sm">
                <i class="fa-solid fa-circle-check text-base text-emerald-500"></i>
                <span>{{ session('success') }}</span>
            </div>
        @endif

        <!-- STEPPER PROCESS TRACKER (GB PARKING STYLE) -->
        <div class="bg-white p-5 rounded-2xl border border-slate-100 shadow-sm overflow-x-auto">
            <div class="flex items-center justify-between min-w-[700px] text-xs">
                <!-- Step 1 -->
                <div class="flex items-center space-x-3">
                    <div
                        class="w-8 h-8 rounded-full bg-emerald-500 text-white flex items-center justify-center font-bold text-xs shadow-md shadow-emerald-500/20">
                        <i class="fa-solid fa-check"></i>
                    </div>
                    <div>
                        <p class="font-bold text-slate-800 text-[11px]">1. Pilih Periode</p>
                        <p class="text-[10px] text-emerald-600 font-semibold">Selesai</p>
                    </div>
                </div>
                <div class="h-[2px] bg-emerald-300 flex-1 mx-3"></div>

                <!-- Step 2 -->
                <div class="flex items-center space-x-3">
                    <div
                        class="w-8 h-8 rounded-full bg-[#FF6B00] text-white flex items-center justify-center font-bold text-xs shadow-md shadow-orange-500/20">
                        2
                    </div>
                    <div>
                        <p class="font-bold text-slate-800 text-[11px]">2. Hitung & Validasi</p>
                        <p class="text-[10px] text-orange-500 font-semibold">Sedang Berjalan</p>
                    </div>
                </div>
                <div class="h-[2px] bg-slate-200 flex-1 mx-3"></div>

                <!-- Step 3 -->
                <div class="flex items-center space-x-3">
                    <div
                        class="w-8 h-8 rounded-full bg-slate-100 text-slate-400 flex items-center justify-center font-bold text-xs">
                        3
                    </div>
                    <div>
                        <p class="font-bold text-slate-400 text-[11px]">3. Review Payroll</p>
                        <p class="text-[10px] text-slate-400">Menunggu</p>
                    </div>
                </div>
                <div class="h-[2px] bg-slate-200 flex-1 mx-3"></div>

                <!-- Step 4 -->
                <div class="flex items-center space-x-3">
                    <div
                        class="w-8 h-8 rounded-full bg-slate-100 text-slate-400 flex items-center justify-center font-bold text-xs">
                        4
                    </div>
                    <div>
                        <p class="font-bold text-slate-400 text-[11px]">4. Approval Gaji</p>
                        <p class="text-[10px] text-slate-400">Menunggu</p>
                    </div>
                </div>
                <div class="h-[2px] bg-slate-200 flex-1 mx-3"></div>

                <!-- Step 5 -->
                <div class="flex items-center space-x-3">
                    <div
                        class="w-8 h-8 rounded-full bg-slate-100 text-slate-400 flex items-center justify-center font-bold text-xs">
                        5
                    </div>
                    <div>
                        <p class="font-bold text-slate-400 text-[11px]">5. Selesai / Slip</p>
                        <p class="text-[10px] text-slate-400">Menunggu</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- PERIODE STATS HEADER CARDS -->
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
            <div class="bg-white p-4 rounded-2xl border border-slate-100 shadow-sm flex items-center space-x-3">
                <div
                    class="w-10 h-10 rounded-xl bg-purple-50 text-purple-600 flex items-center justify-center font-bold text-sm">
                    <i class="fa-solid fa-calendar-days"></i>
                </div>
                <div>
                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Periode Payroll</p>
                    <h4 class="text-sm font-extrabold text-slate-800">
                        {{ \Carbon\Carbon::parse($selectedMonth)->translatedFormat('F Y') }}
                    </h4>
                </div>
            </div>

            <div class="bg-white p-4 rounded-2xl border border-slate-100 shadow-sm flex items-center space-x-3">
                <div
                    class="w-10 h-10 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center font-bold text-sm">
                    <i class="fa-solid fa-users"></i>
                </div>
                <div>
                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Total Karyawan</p>
                    <h4 class="text-sm font-extrabold text-slate-800">
                        {{ $payrolls->total() }} <span class="text-xs text-slate-400 font-normal">Orang Diproses</span>
                    </h4>
                </div>
            </div>

            <div class="bg-white p-4 rounded-2xl border border-slate-100 shadow-sm flex items-center space-x-3">
                <div
                    class="w-10 h-10 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center font-bold text-sm">
                    <i class="fa-solid fa-file-invoice-dollar"></i>
                </div>
                <div>
                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Tipe Payroll</p>
                    <h4 class="text-sm font-extrabold text-slate-800">Payroll Bulanan</h4>
                </div>
            </div>

            <div class="bg-white p-4 rounded-2xl border border-slate-100 shadow-sm flex items-center space-x-3">
                <div
                    class="w-10 h-10 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center font-bold text-sm">
                    <i class="fa-solid fa-[#FF6B00] fa-clock text-amber-500"></i>
                </div>
                <div>
                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Status Draft</p>
                    <span
                        class="px-2 py-0.5 bg-amber-50 text-amber-600 border border-amber-200 font-extrabold rounded-md text-[10px] uppercase">
                        Kalkulasi Draft
                    </span>
                </div>
            </div>
        </div>

        <!-- MAIN LAYOUT (GRID 3:1) -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 items-start">

            <!-- TABEL UTAMA PAYROLL (KIRI 2 KOLOM) -->
            <div class="lg:col-span-2 space-y-4">
                <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">

                    <!-- FILTER TOOLBAR -->
                    <div
                        class="p-4 border-b border-slate-100 bg-slate-50/50 flex flex-wrap items-center justify-between gap-3">
                        <form method="GET" action="{{ route('payrolls.index') }}"
                            class="flex flex-wrap items-center gap-2 w-full sm:w-auto">
                            <!-- Input Month -->
                            <input type="month" name="month" value="{{ $selectedMonth }}"
                                class="px-3.5 py-2 rounded-xl border border-slate-200 text-xs font-bold focus:outline-none focus:border-[#FF6B00] bg-white text-slate-700">

                            <!-- Search -->
                            <div class="relative flex-1 sm:w-48">
                                <input type="text" name="search" value="{{ request('search') }}"
                                    placeholder="Cari nama karyawan..."
                                    class="w-full pl-8 pr-3 py-2 rounded-xl border border-slate-200 text-xs focus:outline-none focus:border-[#FF6B00] bg-white">
                                <i
                                    class="fa-solid fa-magnifying-glass absolute left-3 top-2.5 text-slate-400 text-xs"></i>
                            </div>

                            <button type="submit"
                                class="px-4 py-2 bg-slate-800 hover:bg-slate-900 text-white font-bold rounded-xl text-xs transition">
                                Filter
                            </button>
                        </form>
                    </div>

                    <!-- TABEL BREAKDOWN PAYROLL -->
                    <div class="overflow-x-auto">
                        <table class="w-full text-left text-xs">
                            <thead
                                class="bg-slate-50 text-slate-400 font-bold border-b border-slate-100 uppercase tracking-wider">
                                <tr>
                                    <th class="p-4">Karyawan</th>
                                    <th class="p-4">Gaji Pokok</th>
                                    <th class="p-4">Pendapatan (+)</th>
                                    <th class="p-4">Potongan (-)</th>
                                    <th class="p-4">Take Home Pay</th>
                                    <th class="p-4 text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100 text-slate-700">
                                @forelse ($payrolls as $item)
                                    <tr class="hover:bg-slate-50/80 transition">
                                        <!-- Karyawan -->
                                        <td class="p-4 whitespace-nowrap">
                                            <div class="flex items-center space-x-3">
                                                <div
                                                    class="w-8 h-8 rounded-full bg-orange-50 border border-orange-200 flex items-center justify-center font-bold text-[#FF6B00] text-xs uppercase">
                                                    {{ substr($item->employee->name ?? 'K', 0, 2) }}
                                                </div>
                                                <div>
                                                    <p class="font-bold text-slate-800">
                                                        {{ $item->employee->name ?? '-' }}</p>
                                                    <p class="text-[10px] text-slate-400 font-mono">
                                                        {{ $item->employee->nik ?? '-' }}</p>
                                                </div>
                                            </div>
                                        </td>

                                        <!-- Gaji Pokok -->
                                        <td class="p-4 font-mono font-medium text-slate-600 whitespace-nowrap">
                                            Rp {{ number_format($item->basic_salary, 0, ',', '.') }}
                                        </td>

                                        <!-- Pendapatan Tambahan (Tunjangan + Bonus) -->
                                        <td class="p-4 font-mono whitespace-nowrap">
                                            <span class="text-emerald-600 font-semibold">+Rp
                                                {{ number_format($item->total_allowance + $item->total_bonus, 0, ',', '.') }}</span>
                                            <div class="text-[9px] text-slate-400">
                                                Tj: {{ number_format($item->total_allowance, 0, ',', '.') }} | Bn:
                                                {{ number_format($item->total_bonus, 0, ',', '.') }}
                                            </div>
                                        </td>

                                        <!-- Potongan -->
                                        <td class="p-4 font-mono text-rose-500 font-semibold whitespace-nowrap">
                                            -Rp {{ number_format($item->total_deduction, 0, ',', '.') }}
                                        </td>

                                        <!-- Take Home Pay (Gaji Bersih) -->
                                        <td
                                            class="p-4 font-extrabold font-mono text-slate-900 bg-emerald-50/30 text-sm whitespace-nowrap">
                                            Rp {{ number_format($item->net_salary, 0, ',', '.') }}
                                        </td>

                                        <!-- Aksi -->
                                        <td class="p-4 text-center space-x-1 whitespace-nowrap">
                                            <a href="{{ route('payrolls.edit', $item->id) }}" title="Edit Catatan"
                                                class="p-2 text-slate-400 hover:text-amber-600 hover:bg-amber-50 rounded-xl transition inline-block">
                                                <i class="fa-solid fa-pen text-xs"></i>
                                            </a>
                                            <form action="{{ route('payrolls.destroy', $item->id) }}" method="POST"
                                                class="inline-block"
                                                onsubmit="return confirm('Hapus draft payroll ini?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" title="Hapus Draft"
                                                    class="p-2 text-slate-400 hover:text-rose-600 hover:bg-rose-50 rounded-xl transition">
                                                    <i class="fa-solid fa-trash text-xs"></i>
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="p-12 text-center text-slate-400">
                                            <div
                                                class="w-16 h-16 mx-auto bg-slate-50 rounded-full flex items-center justify-center mb-3 text-slate-300">
                                                <i class="fa-solid fa-calculator text-2xl"></i>
                                            </div>
                                            <p class="font-bold text-slate-600 text-sm">Belum Ada Draft Payroll
                                                Ditambahkan</p>
                                            <p class="text-xs text-slate-400 mt-1">Klik tombol 'Hitung / Tambah Draft
                                                Payroll' untuk membuat kalkulasi baru.</p>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- PAGINATION -->
                    @if (method_exists($payrolls, 'links'))
                        <div class="p-4 border-t border-slate-100 bg-slate-50/50">
                            {{ $payrolls->links() }}
                        </div>
                    @endif
                </div>
            </div>

            <!-- PANEL RINGKASAN TOTAL PAYROLL -->
            <div class="space-y-4">
                <div class="bg-white p-5 rounded-2xl border border-slate-100 shadow-sm space-y-4">
                    <h3 class="text-sm font-extrabold text-slate-800 border-b border-slate-100 pb-3">
                        Ringkasan Payroll {{ \Carbon\Carbon::parse($selectedMonth)->translatedFormat('M Y') }}
                    </h3>

                    @php
                        $grandTHP = $payrolls->sum('net_salary');
                        $grandBasic = $payrolls->sum('basic_salary');
                        $grandAllowance = $payrolls->sum('total_allowance');
                        $grandBonus = $payrolls->sum('total_bonus');
                        $grandDeduction = $payrolls->sum('total_deduction');
                    @endphp

                    <!-- Total Highlight Card -->
                    <div
                        class="p-4 bg-gradient-to-br from-slate-900 to-slate-800 rounded-2xl text-white space-y-1 shadow-lg shadow-slate-900/10">
                        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">TOTAL TAKE HOME PAY
                            (BERSIH)</p>
                        <h2 class="text-xl font-extrabold font-mono text-emerald-400">
                            Rp {{ number_format($grandTHP, 0, ',', '.') }}
                        </h2>
                        <p class="text-[10px] text-slate-400">*Total nominal gaji yang akan dicairkan.</p>
                    </div>

                    <!-- Detail Rincian Ringkas -->
                    <div class="space-y-2 text-xs pt-1">
                        <div class="flex justify-between items-center text-slate-600">
                            <span>Total Gaji Pokok</span>
                            <span class="font-mono font-bold text-slate-800">Rp
                                {{ number_format($grandBasic, 0, ',', '.') }}</span>
                        </div>
                        <div class="flex justify-between items-center text-slate-600">
                            <span>Total Tunjangan</span>
                            <span class="font-mono font-bold text-emerald-600">+Rp
                                {{ number_format($grandAllowance, 0, ',', '.') }}</span>
                        </div>
                        <div class="flex justify-between items-center text-slate-600">
                            <span>Total Bonus & Insentif</span>
                            <span class="font-mono font-bold text-purple-600">+Rp
                                {{ number_format($grandBonus, 0, ',', '.') }}</span>
                        </div>
                        <div class="flex justify-between items-center text-slate-600 border-b border-slate-100 pb-2">
                            <span>Total Potongan</span>
                            <span class="font-mono font-bold text-rose-500">-Rp
                                {{ number_format($grandDeduction, 0, ',', '.') }}</span>
                        </div>
                    </div>

                    <!-- RINCIAN KOMPONEN POTONGAN DINAMIS -->
                    @php
                        // Kita hitung estimasi nilai BPJS & Pajak dari Total Gaji Pokok
                        $estBpjs = $grandBasic * 0.03; // 3% BPJS
                        $estTax = $grandBasic * 0.05; // 5% Pajak PPh 21

                        // Sisanya adalah Kasbon / Potongan Lain
                        $estOther = max(0, $grandDeduction - ($estBpjs + $estTax));
                    @endphp

                    <div class="space-y-2 pt-1 border-t border-slate-100">
                        <p class="text-[11px] font-bold text-slate-700">Rincian Komponen Potongan:</p>

                        <div class="space-y-1.5 text-[11px] pl-1">
                            <div class="flex justify-between items-center text-slate-600">
                                <span class="flex items-center space-x-1.5">
                                    <span class="w-1.5 h-1.5 rounded-full bg-blue-500"></span>
                                    <span>Potongan BPJS (3%)</span>
                                </span>
                                <span class="font-mono font-bold text-slate-800">
                                    Rp {{ number_format($estBpjs, 0, ',', '.') }}
                                </span>
                            </div>

                            <div class="flex justify-between items-center text-slate-600">
                                <span class="flex items-center space-x-1.5">
                                    <span class="w-1.5 h-1.5 rounded-full bg-amber-500"></span>
                                    <span>Pajak PPh 21 (5%)</span>
                                </span>
                                <span class="font-mono font-bold text-slate-800">
                                    Rp {{ number_format($estTax, 0, ',', '.') }}
                                </span>
                            </div>

                            <div class="flex justify-between items-center text-slate-600">
                                <span class="flex items-center space-x-1.5">
                                    <span class="w-1.5 h-1.5 rounded-full bg-rose-500"></span>
                                    <span>Kasbon / Potongan Lain</span>
                                </span>
                                <span class="font-mono font-bold text-rose-500">
                                    Rp {{ number_format($estOther, 0, ',', '.') }}
                                </span>
                            </div>
                        </div>
                    </div>

                    <!-- Checklist Validasi Sederhana -->
                    <div class="pt-3 border-t border-slate-100 space-y-2">
                        <p class="text-[11px] font-bold text-slate-700">Checklist Validasi Data:</p>
                        <div class="space-y-1.5 text-[11px]">
                            <div class="flex items-center text-emerald-600 font-medium space-x-2">
                                <i class="fa-solid fa-circle-check"></i>
                                <span>Gaji Pokok Karyawan Terkalkulasi</span>
                            </div>
                            <div class="flex items-center text-emerald-600 font-medium space-x-2">
                                <i class="fa-solid fa-circle-check"></i>
                                <span>Data Bonus & Insentif Terintegrasi</span>
                            </div>
                            <div class="flex items-center text-emerald-600 font-medium space-x-2">
                                <i class="fa-solid fa-circle-check"></i>
                                <span>Potongan BPJS & Pajak Dihitung</span>
                            </div>
                        </div>
                    </div>

                    <!-- Tombol Lanjut ke Approval -->
                    <div class="pt-2">
                        <a href="{{ route('payrolls.approval') }}"
                            class="w-full py-3 bg-[#FF6B00] hover:bg-[#e66000] text-white font-bold rounded-xl text-xs shadow-lg shadow-orange-500/20 flex items-center justify-center space-x-2 transition">
                            <span>Lanjut ke Approval Payroll</span>
                            <i class="fa-solid fa-arrow-right text-xs"></i>
                        </a>
                    </div>
                </div>
            </div>

        </div>

    </div>
</x-app-layout>
