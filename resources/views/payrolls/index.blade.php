<x-app-layout>
    <div class="space-y-6">

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

        @if (session('success'))
            <div
                class="p-4 bg-emerald-50 border border-emerald-200 text-emerald-700 text-xs font-semibold rounded-2xl flex items-center space-x-2 shadow-sm">
                <i class="fa-solid fa-circle-check text-base text-emerald-500"></i>
                <span>{{ session('success') }}</span>
            </div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

            <div class="lg:col-span-2 bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
                <!-- Filter Bar -->
                <div class="p-4 border-b border-slate-100 flex flex-wrap items-center justify-between gap-2">
                    <h3 class="text-xs font-bold text-slate-800 uppercase tracking-wider">Draft Gaji Siap Diproses</h3>

                    <form method="GET" action="{{ route('payrolls.index') }}" class="flex items-center space-x-2">
                        <!-- Select Tipe Payroll -->
                        <select name="payroll_type" onchange="this.form.submit()"
                            class="px-3 py-1.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-bold text-slate-700 focus:outline-none focus:border-[#FF6B00]">
                            <option value="Bulanan" {{ $payrollType == 'Bulanan' ? 'selected' : '' }}>Bulanan</option>
                            <option value="Harian" {{ $payrollType == 'Harian' ? 'selected' : '' }}>Harian</option>
                        </select>

                        <!-- Input Bulan -->
                        <input type="month" name="month" value="{{ $selectedMonth }}" onchange="this.form.submit()"
                            class="px-3 py-1.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-bold text-slate-700 focus:outline-none focus:border-[#FF6B00]">
                    </form>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left text-xs">
                        <thead
                            class="bg-slate-50 text-slate-400 font-bold border-b border-slate-100 uppercase tracking-wider">
                            <tr>
                                <th class="p-4">Karyawan</th>
                                <th class="p-4">Tipe & Periode</th>
                                <th class="p-4 text-right">Take Home Pay</th>
                                <th class="p-4 text-center">Status</th>
                                <th class="p-4 text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 text-slate-700">
                            @forelse ($payrolls as $payroll)
                                <tr class="hover:bg-slate-50/60 transition">
                                    <td class="p-4 font-bold text-slate-800">
                                        <div>{{ $payroll->employee->name ?? 'Karyawan Dihapus' }}</div>
                                        <div class="text-[10px] text-slate-400 font-normal">
                                            {{ $payroll->employee->nik ?? '-' }}</div>
                                    </td>
                                    <td class="p-4">
                                        @if (($payroll->payroll_type ?? 'Bulanan') === 'Harian')
                                            <span
                                                class="px-2 py-0.5 bg-purple-100 text-purple-700 rounded-md font-bold text-[10px]">Harian</span>
                                            <div class="text-[10px] text-slate-500 mt-0.5">
                                                {{ $payroll->payroll_date ? \Carbon\Carbon::parse($payroll->payroll_date)->format('d M Y') : '-' }}
                                            </div>
                                        @else
                                            <span
                                                class="px-2 py-0.5 bg-blue-100 text-blue-700 rounded-md font-bold text-[10px]">Bulanan</span>
                                            <div class="text-[10px] text-slate-500 mt-0.5">{{ $payroll->month_year }}
                                            </div>
                                        @endif
                                    </td>
                                    <td class="p-4 text-right font-extrabold text-emerald-600 font-mono">
                                        Rp {{ number_format($payroll->net_salary, 0, ',', '.') }}
                                    </td>
                                    <td class="p-4 text-center">
                                        <span
                                            class="px-2.5 py-1 bg-amber-100/80 text-amber-800 font-bold rounded-lg text-[10px] uppercase">
                                            {{ $payroll->status }}
                                        </span>
                                    </td>
                                    <td class="p-4 text-center space-x-1">
                                        <a href="{{ route('payrolls.edit', $payroll->id) }}"
                                            class="p-2 text-amber-500 hover:bg-amber-50 rounded-lg inline-block">
                                            <i class="fa-solid fa-pen"></i>
                                        </a>
                                        <form id="delete-form-{{ $payroll->id }}"
                                            action="{{ route('payrolls.destroy', $payroll->id) }}" method="POST"
                                            class="inline-block">
                                            @csrf
                                            @method('DELETE')
                                            <button type="button" onclick="confirmDelete({{ $payroll->id }})"
                                                class="p-2 text-red-500 hover:bg-red-50 rounded-lg">
                                                <i class="fa-solid fa-trash"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="p-8 text-center text-slate-400">
                                        <i class="fa-solid fa-receipt text-3xl mb-2 block text-slate-300"></i>
                                        Belum ada draft payroll untuk periode ini.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if (method_exists($payrolls, 'links'))
                    <div class="p-4 border-t border-slate-100">
                        {{ $payrolls->links() }}
                    </div>
                @endif
            </div>

            <div class="space-y-4">
                <div class="bg-white p-5 rounded-2xl border border-slate-100 shadow-sm space-y-4">
                    <h3 class="text-xs font-bold text-slate-800 uppercase tracking-wider">Langkah Selanjutnya</h3>
                    <p class="text-xs text-slate-500 leading-relaxed">
                        Setelah semua draft payroll diperiksa, silakan lanjutkan ke menu **Approval Payroll** untuk
                        persetujuan pimpinan/HRD.
                    </p>
                    <a href="{{ route('payrolls.approval') }}"
                        class="w-full py-3 bg-[#FF6B00] hover:bg-[#e66000] text-white font-bold rounded-xl text-xs shadow-lg shadow-orange-500/20 flex items-center justify-center space-x-2 transition">
                        <span>Lanjut ke Approval Payroll</span>
                        <i class="fa-solid fa-arrow-right text-xs"></i>
                    </a>
                </div>
            </div>

        </div>

    </div>

    <!-- Script SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        function confirmDelete(id) {
            Swal.fire({
                title: 'Hapus Draft Payroll?',
                text: "Data yang dihapus tidak dapat dikembalikan!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#ef4444',
                cancelButtonColor: '#64748b',
                confirmButtonText: 'Ya, Hapus!',
                cancelButtonText: 'Batal',
                customClass: {
                    popup: 'rounded-2xl',
                    confirmButton: 'rounded-xl text-xs font-bold px-4 py-2.5',
                    cancelButton: 'rounded-xl text-xs font-bold px-4 py-2.5'
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('delete-form-' + id).submit();
                }
            });
        }
    </script>
</x-app-layout>
