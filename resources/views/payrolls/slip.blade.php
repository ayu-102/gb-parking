<x-app-layout>
    <div class="space-y-6">

        <!-- HEADER PAGE -->
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div>
                <h1 class="text-2xl font-bold text-slate-800 tracking-tight">Slip Gaji Official</h1>
                <p class="text-xs text-slate-500 mt-1">Arsip dan cetak slip gaji resmi karyawan yang telah disetujui
                    (Approved).</p>
            </div>
            <div class="flex items-center space-x-2">
                <a href="{{ route('payrolls.approval') }}"
                    class="px-4 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold rounded-xl text-xs flex items-center space-x-2 transition">
                    <i class="fa-solid fa-arrow-left text-xs"></i>
                    <span>Kembali ke Approval</span>
                </a>
            </div>
        </div>

        <!-- STEPPER PROCESS TRACKER (GB PARKING STYLE - STEP 5 COMPLETED) -->
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
                        class="w-8 h-8 rounded-full bg-emerald-500 text-white flex items-center justify-center font-bold text-xs shadow-md shadow-emerald-500/20">
                        <i class="fa-solid fa-check"></i>
                    </div>
                    <div>
                        <p class="font-bold text-slate-800 text-[11px]">2. Hitung & Validasi</p>
                        <p class="text-[10px] text-emerald-600 font-semibold">Selesai</p>
                    </div>
                </div>
                <div class="h-[2px] bg-emerald-300 flex-1 mx-3"></div>

                <!-- Step 3 -->
                <div class="flex items-center space-x-3">
                    <div
                        class="w-8 h-8 rounded-full bg-emerald-500 text-white flex items-center justify-center font-bold text-xs shadow-md shadow-emerald-500/20">
                        <i class="fa-solid fa-check"></i>
                    </div>
                    <div>
                        <p class="font-bold text-slate-800 text-[11px]">3. Review Payroll</p>
                        <p class="text-[10px] text-emerald-600 font-semibold">Selesai</p>
                    </div>
                </div>
                <div class="h-[2px] bg-emerald-300 flex-1 mx-3"></div>

                <!-- Step 4 -->
                <div class="flex items-center space-x-3">
                    <div
                        class="w-8 h-8 rounded-full bg-emerald-500 text-white flex items-center justify-center font-bold text-xs shadow-md shadow-emerald-500/20">
                        <i class="fa-solid fa-check"></i>
                    </div>
                    <div>
                        <p class="font-bold text-slate-800 text-[11px]">4. Approval Gaji</p>
                        <p class="text-[10px] text-emerald-600 font-semibold">Disetujui</p>
                    </div>
                </div>
                <div class="h-[2px] bg-emerald-300 flex-1 mx-3"></div>

                <!-- Step 5 (ACTIVE - FINAL) -->
                <div class="flex items-center space-x-3">
                    <div
                        class="w-8 h-8 rounded-full bg-emerald-600 text-white flex items-center justify-center font-bold text-xs shadow-md shadow-emerald-600/20">
                        <i class="fa-solid fa-receipt"></i>
                    </div>
                    <div>
                        <p class="font-bold text-slate-800 text-[11px]">5. Slip Gaji Resmi</p>
                        <p class="text-[10px] text-emerald-600 font-semibold">Siap Dicetak</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- STATS BAR -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div class="bg-white p-4 rounded-2xl border border-slate-100 shadow-sm flex items-center space-x-3">
                <div
                    class="w-10 h-10 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center font-bold text-sm">
                    <i class="fa-solid fa-file-invoice font-bold"></i>
                </div>
                <div>
                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Total Slip Terbit</p>
                    <h4 class="text-sm font-extrabold text-slate-800">
                        {{ $payrolls->total() }} <span class="text-xs text-slate-400 font-normal">Dokumen</span>
                    </h4>
                </div>
            </div>

            <div class="bg-white p-4 rounded-2xl border border-slate-100 shadow-sm flex items-center space-x-3">
                <div
                    class="w-10 h-10 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center font-bold text-sm">
                    <i class="fa-solid fa-money-bill-wave"></i>
                </div>
                <div>
                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Total Pencairan THP</p>
                    <h4 class="text-sm font-extrabold text-emerald-600 font-mono">
                        Rp {{ number_format($payrolls->sum('net_salary'), 0, ',', '.') }}
                    </h4>
                </div>
            </div>

            <div class="bg-white p-4 rounded-2xl border border-slate-100 shadow-sm flex items-center space-x-3">
                <div
                    class="w-10 h-10 rounded-xl bg-purple-50 text-purple-600 flex items-center justify-center font-bold text-sm">
                    <i class="fa-solid fa-calendar-days"></i>
                </div>
                <div>
                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Periode Aktif</p>
                    <h4 class="text-sm font-extrabold text-slate-800">
                        {{ \Carbon\Carbon::parse($selectedMonth)->translatedFormat('F Y') }}
                    </h4>
                </div>
            </div>
        </div>

        <!-- MAIN TABLE CONTAINER -->
        <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
            <!-- Filter Bar -->
            <div class="p-4 border-b border-slate-100 flex flex-wrap items-center justify-between gap-4 bg-slate-50/50">
                <form method="GET" action="{{ route('payrolls.slip') }}" class="flex items-center space-x-2">
                    <input type="month" name="month" value="{{ $selectedMonth }}"
                        class="px-3.5 py-2 rounded-xl border border-slate-200 text-xs font-bold focus:outline-none focus:border-[#FF6B00] bg-white text-slate-700">
                    <button type="submit"
                        class="px-4 py-2 bg-slate-800 hover:bg-slate-900 text-white font-bold rounded-xl text-xs transition">
                        Filter Periode
                    </button>
                </form>

                <div class="text-[11px] text-slate-400">
                    <i class="fa-solid fa-shield-check text-emerald-500 mr-1"></i>
                    <span>Hanya menampilkan payroll berstatus <b>Approved</b></span>
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
                            <th class="p-4">Tunjangan</th>
                            <th class="p-4">Bonus / Insentif</th>
                            <th class="p-4">Total Potongan</th>
                            <th class="p-4">Take Home Pay</th>
                            <th class="p-4 text-center">Aksi Dokumen</th>
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
                                    class="p-4 font-extrabold font-mono text-slate-900 bg-emerald-50/30 text-sm whitespace-nowrap">
                                    Rp {{ number_format($item->net_salary, 0, ',', '.') }}
                                </td>
                                <td class="p-4 text-center whitespace-nowrap">
                                    <a href="{{ route('payrolls.print', $item->id) }}" target="_blank"
                                        class="px-3.5 py-2 bg-slate-900 hover:bg-slate-800 text-white font-bold rounded-xl text-[11px] shadow-sm transition inline-flex items-center space-x-1.5 shadow-slate-900/10">
                                        <i class="fa-solid fa-print text-xs"></i>
                                        <span>Cetak Slip</span>
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="p-12 text-center text-slate-400">
                                    <div
                                        class="w-16 h-16 mx-auto bg-slate-50 rounded-full flex items-center justify-center mb-3 text-slate-300">
                                        <i class="fa-solid fa-receipt text-2xl"></i>
                                    </div>
                                    <p class="font-bold text-slate-600 text-sm">Belum Ada Slip Gaji yang Disetujui</p>
                                    <p class="text-xs text-slate-400 mt-1">Pastikan Anda telah menyetujui (Approve)
                                        draft payroll pada menu Approval Payroll.</p>
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
