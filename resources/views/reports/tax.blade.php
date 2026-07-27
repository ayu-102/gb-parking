<x-app-layout>
    <div class="space-y-6">
        <!-- HEADER PAGE -->
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div>
                <h1 class="text-2xl font-bold text-slate-800 tracking-tight">Laporan Pemotongan Pajak (PPh 21)</h1>
                <p class="text-xs text-slate-500 mt-1">Rekapitulasi resmi Pemotongan Pajak Penghasilan Pasal 21 Karyawan.
                </p>
            </div>
            <div class="flex items-center space-x-2">
                <button onclick="window.print()"
                    class="px-4 py-2.5 bg-slate-900 hover:bg-slate-800 text-white font-bold rounded-xl text-xs shadow-sm transition flex items-center space-x-2">
                    <i class="fa-solid fa-print text-xs"></i>
                    <span>Cetak Laporan Pajak</span>
                </button>
            </div>
        </div>

        <!-- STATS SUMMARY CARDS -->
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
            <!-- Card 1: Total Wajib Pajak -->
            <div class="bg-white p-4 rounded-2xl border border-slate-100 shadow-sm flex items-center space-x-3.5">
                <div
                    class="w-11 h-11 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center font-bold text-base shrink-0">
                    <i class="fa-solid fa-user-shield"></i>
                </div>
                <div class="overflow-hidden">
                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Total Wajib Pajak</p>
                    <h4 class="text-base font-extrabold text-slate-800 tracking-tight">
                        {{ number_format($totalEmployees) }} <span
                            class="text-xs text-slate-400 font-normal">Karyawan</span>
                    </h4>
                </div>
            </div>

            <!-- Card 2: Total Penghasilan Bruto -->
            <div class="bg-white p-4 rounded-2xl border border-slate-100 shadow-sm flex items-center space-x-3.5">
                <div
                    class="w-11 h-11 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center font-bold text-base shrink-0">
                    <i class="fa-solid fa-file-invoice-dollar"></i>
                </div>
                <div class="overflow-hidden">
                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Total Gaji Bruto</p>
                    <h4 class="text-base font-extrabold text-slate-800 font-mono tracking-tight truncate">
                        Rp {{ number_format($totalGrossSalary, 0, ',', '.') }}
                    </h4>
                </div>
            </div>

            <!-- Card 3: Total Setoran PPh 21 -->
            <div
                class="bg-white p-4 rounded-2xl border border-slate-100 shadow-sm flex items-center space-x-3.5 border-l-4 border-l-amber-500">
                <div
                    class="w-11 h-11 rounded-xl bg-amber-500 text-white flex items-center justify-center font-bold text-base shrink-0 shadow-md shadow-amber-500/20">
                    <i class="fa-solid fa-building-columns"></i>
                </div>
                <div class="overflow-hidden">
                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Total Potongan PPh 21 (5%)
                    </p>
                    <h4 class="text-base font-extrabold text-amber-600 font-mono tracking-tight truncate">
                        Rp {{ number_format($totalTax, 0, ',', '.') }}
                    </h4>
                </div>
            </div>
        </div>

        <!-- MAIN TABLE CONTAINER -->
        <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
            <!-- Filter Bar & Periode Tag -->
            <div class="p-4 border-b border-slate-100 flex flex-wrap items-center justify-between gap-4 bg-slate-50/50">
                <form method="GET" action="{{ route('reports.tax') }}" class="flex items-center space-x-2">
                    <input type="month" name="month" value="{{ $selectedMonth }}"
                        class="px-3.5 py-2 rounded-xl border border-slate-200 text-xs font-bold focus:outline-none focus:border-[#FF6B00] bg-white text-slate-700">
                    <button type="submit"
                        class="px-4 py-2 bg-slate-800 hover:bg-slate-900 text-white font-bold rounded-xl text-xs transition">
                        Filter Laporan
                    </button>
                </form>

                <div class="flex items-center space-x-2 text-[11px] text-slate-500 font-semibold">
                    <span class="px-2.5 py-1 bg-amber-100/80 text-amber-700 rounded-lg">
                        <i class="fa-solid fa-calendar-day mr-1"></i>
                        Periode Pajak: {{ \Carbon\Carbon::parse($selectedMonth)->translatedFormat('F Y') }}
                    </span>
                </div>
            </div>

            <!-- Table Data -->
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs">
                    <thead
                        class="bg-slate-50 text-slate-400 font-bold border-b border-slate-100 uppercase tracking-wider">
                        <tr>
                            <th class="p-4">Karyawan</th>
                            <th class="p-4">Periode</th>
                            <th class="p-4">Gaji Pokok</th>
                            <th class="p-4">Gaji Bruto (Kotor)</th>
                            <th class="p-4">Potongan PPh 21 (5%)</th>
                            <th class="p-4 text-center">Status Setor</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 text-slate-700">
                        @forelse ($payrolls as $item)
                            @php
                                $grossSalary = $item->basic_salary + $item->total_allowance + $item->total_bonus;
                                $taxAmount = $item->basic_salary * 0.05; // Hitungan 5% Pajak
                            @endphp
                            <tr class="hover:bg-slate-50/80 transition">
                                <td class="p-4 whitespace-nowrap">
                                    <div class="flex items-center space-x-3">
                                        <div
                                            class="w-8 h-8 rounded-full bg-slate-100 border border-slate-200 flex items-center justify-center font-bold text-slate-600 text-xs uppercase">
                                            {{ substr($item->employee->name ?? 'K', 0, 2) }}
                                        </div>
                                        <div>
                                            <p class="font-bold text-slate-800">{{ $item->employee->name ?? '-' }}</p>
                                            <p class="text-[10px] text-slate-400 font-mono">
                                                {{ $item->employee->nik ?? '' }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="p-4 font-semibold text-slate-600 whitespace-nowrap">
                                    {{ \Carbon\Carbon::parse($item->month_year)->translatedFormat('M Y') }}
                                </td>
                                <td class="p-4 font-mono text-slate-600 whitespace-nowrap">
                                    Rp {{ number_format($item->basic_salary, 0, ',', '.') }}
                                </td>
                                <td class="p-4 font-mono text-slate-800 font-semibold whitespace-nowrap">
                                    Rp {{ number_format($grossSalary, 0, ',', '.') }}
                                </td>
                                <td
                                    class="p-4 font-mono text-amber-600 font-extrabold bg-amber-50/30 whitespace-nowrap">
                                    -Rp {{ number_format($taxAmount, 0, ',', '.') }}
                                </td>
                                <td class="p-4 text-center whitespace-nowrap">
                                    @if ($taxAmount > 0)
                                        <span
                                            class="px-2.5 py-1 bg-emerald-100/80 text-emerald-700 font-bold rounded-lg text-[10px] uppercase tracking-wide inline-flex items-center gap-1">
                                            <i class="fa-solid fa-circle-check text-[9px]"></i> Terpotong
                                        </span>
                                    @else
                                        <span
                                            class="px-2.5 py-1 bg-slate-100 text-slate-500 font-bold rounded-lg text-[10px] uppercase tracking-wide">
                                            Nihil
                                        </span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="p-12 text-center text-slate-400">
                                    <div
                                        class="w-16 h-16 mx-auto bg-slate-50 rounded-full flex items-center justify-center mb-3 text-slate-300">
                                        <i class="fa-solid fa-receipt text-2xl"></i>
                                    </div>
                                    <p class="font-bold text-slate-600 text-sm">Tidak Ada Data Pajak PPh 21</p>
                                    <p class="text-xs text-slate-400 mt-1">Belum ada data pemotongan pajak yang
                                        berstatus Approved untuk periode ini.</p>
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
</x-app-layout>
