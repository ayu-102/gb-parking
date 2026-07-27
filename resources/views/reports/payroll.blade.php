<x-app-layout>
    <div class="space-y-6">
        <!-- HEADER PAGE -->
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div>
                <h1 class="text-2xl font-bold text-slate-800 tracking-tight">Laporan Rekapitulasi Payroll</h1>
                <p class="text-xs text-slate-500 mt-1">Ringkasan pengeluaran gaji resmi karyawan yang telah disetujui
                    (Approved).</p>
            </div>
            <div class="flex items-center space-x-2">
                <button onclick="window.print()"
                    class="px-4 py-2.5 bg-slate-900 hover:bg-slate-800 text-white font-bold rounded-xl text-xs shadow-sm transition flex items-center space-x-2">
                    <i class="fa-solid fa-print text-xs"></i>
                    <span>Cetak Laporan</span>
                </button>
            </div>
        </div>

        <!-- STATS SUMMARY CARDS (GB PARKING STYLE) -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
            <!-- Card 1: Total Karyawan -->
            <div class="bg-white p-4 rounded-2xl border border-slate-100 shadow-sm flex items-center space-x-3.5">
                <div
                    class="w-11 h-11 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center font-bold text-base shrink-0">
                    <i class="fa-solid fa-users"></i>
                </div>
                <div class="overflow-hidden">
                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Total Karyawan</p>
                    <h4 class="text-base font-extrabold text-slate-800 tracking-tight">
                        {{ number_format($totalEmployees) }} <span
                            class="text-xs text-slate-400 font-normal">Orang</span>
                    </h4>
                </div>
            </div>

            <!-- Card 2: Total Pendapatan Kotor -->
            <div class="bg-white p-4 rounded-2xl border border-slate-100 shadow-sm flex items-center space-x-3.5">
                <div
                    class="w-11 h-11 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center font-bold text-base shrink-0">
                    <i class="fa-solid fa-wallet"></i>
                </div>
                <div class="overflow-hidden">
                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Total Pendapatan</p>
                    <h4 class="text-base font-extrabold text-slate-800 font-mono tracking-tight truncate">
                        Rp {{ number_format($totalIncome, 0, ',', '.') }}
                    </h4>
                </div>
            </div>

            <!-- Card 3: Total Potongan -->
            <div class="bg-white p-4 rounded-2xl border border-slate-100 shadow-sm flex items-center space-x-3.5">
                <div
                    class="w-11 h-11 rounded-xl bg-rose-50 text-rose-500 flex items-center justify-center font-bold text-base shrink-0">
                    <i class="fa-solid fa-circle-minus"></i>
                </div>
                <div class="overflow-hidden">
                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Total Potongan</p>
                    <h4 class="text-base font-extrabold text-rose-500 font-mono tracking-tight truncate">
                        -Rp {{ number_format($totalDeduction, 0, ',', '.') }}
                    </h4>
                </div>
            </div>

            <!-- Card 4: Total Take Home Pay -->
            <div
                class="bg-white p-4 rounded-2xl border border-slate-100 shadow-sm flex items-center space-x-3.5 border-l-4 border-l-emerald-500">
                <div
                    class="w-11 h-11 rounded-xl bg-emerald-500 text-white flex items-center justify-center font-bold text-base shrink-0 shadow-md shadow-emerald-500/20">
                    <i class="fa-solid fa-money-bill-wave"></i>
                </div>
                <div class="overflow-hidden">
                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Total Take Home Pay</p>
                    <h4 class="text-base font-extrabold text-emerald-600 font-mono tracking-tight truncate">
                        Rp {{ number_format($totalTHP, 0, ',', '.') }}
                    </h4>
                </div>
            </div>
        </div>

        <!-- MAIN TABLE CONTAINER -->
        <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
            <!-- Filter Bar & Search Info -->
            <div class="p-4 border-b border-slate-100 flex flex-wrap items-center justify-between gap-4 bg-slate-50/50">
                <form method="GET" action="{{ route('reports.payroll') }}" class="flex items-center space-x-2">
                    <input type="month" name="month" value="{{ $selectedMonth }}"
                        class="px-3.5 py-2 rounded-xl border border-slate-200 text-xs font-bold focus:outline-none focus:border-[#FF6B00] bg-white text-slate-700">
                    <button type="submit"
                        class="px-4 py-2 bg-slate-800 hover:bg-slate-900 text-white font-bold rounded-xl text-xs transition">
                        Filter Laporan
                    </button>
                </form>

                <div class="flex items-center space-x-2 text-[11px] text-slate-500 font-semibold">
                    <span class="px-2.5 py-1 bg-emerald-100/80 text-emerald-700 rounded-lg">
                        <i class="fa-solid fa-calendar-day mr-1"></i>
                        Periode: {{ \Carbon\Carbon::parse($selectedMonth)->translatedFormat('F Y') }}
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
                            <th class="p-4">Total Tunjangan</th>
                            <th class="p-4">Total Bonus</th>
                            <th class="p-4">Total Potongan</th>
                            <th class="p-4">Take Home Pay (THP)</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 text-slate-700">
                        @forelse ($payrolls as $item)
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
                                <td class="p-4 font-mono text-blue-600 font-semibold whitespace-nowrap">
                                    +Rp {{ number_format($item->total_allowance, 0, ',', '.') }}
                                </td>
                                <td class="p-4 font-mono text-emerald-600 font-semibold whitespace-nowrap">
                                    +Rp {{ number_format($item->total_bonus, 0, ',', '.') }}
                                </td>
                                <td class="p-4 font-mono text-rose-500 font-semibold whitespace-nowrap">
                                    -Rp {{ number_format($item->total_deduction, 0, ',', '.') }}
                                </td>
                                <td
                                    class="p-4 font-extrabold font-mono text-slate-900 bg-emerald-50/40 text-sm whitespace-nowrap">
                                    Rp {{ number_format($item->net_salary, 0, ',', '.') }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="p-12 text-center text-slate-400">
                                    <div
                                        class="w-16 h-16 mx-auto bg-slate-50 rounded-full flex items-center justify-center mb-3 text-slate-300">
                                        <i class="fa-solid fa-folder-open text-2xl"></i>
                                    </div>
                                    <p class="font-bold text-slate-600 text-sm">Tidak Ada Data Laporan Payroll</p>
                                    <p class="text-xs text-slate-400 mt-1">Belum ada data gaji yang berstatus Approved
                                        untuk periode ini.</p>
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
