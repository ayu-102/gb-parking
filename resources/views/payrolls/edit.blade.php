<x-app-layout>
    <div class="max-w-xl mx-auto space-y-6">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-xl font-bold text-slate-800">Edit Catatan Payroll</h1>
                <p class="text-xs text-slate-500 mt-1">Karyawan: {{ $payroll->employee->name }}
                    ({{ $payroll->month_year }})</p>
            </div>
            <!-- Tombol Kembali membawa parameter filter -->
            <a href="{{ route('payrolls.index', ['month' => $payroll->month_year, 'payroll_type' => $payroll->payroll_type]) }}"
                class="px-3.5 py-2 bg-slate-100 text-slate-600 font-bold rounded-xl text-xs hover:bg-slate-200 transition">
                <i class="fa-solid fa-arrow-left mr-1"></i> Kembali
            </a>
        </div>

        <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-6">
            <form action="{{ route('payrolls.update', $payroll->id) }}" method="POST" class="space-y-4">
                @csrf
                @method('PUT')

                <div class="p-4 bg-slate-50 rounded-xl text-xs space-y-1 text-slate-600">
                    <div><span class="font-bold">Gaji Pokok:</span> Rp
                        {{ number_format($payroll->basic_salary, 0, ',', '.') }}</div>
                    <div><span class="font-bold">Total Bonus:</span> Rp
                        {{ number_format($payroll->total_bonus, 0, ',', '.') }}</div>
                    <div><span class="font-bold">Take Home Pay:</span> <span class="font-bold text-emerald-600">Rp
                            {{ number_format($payroll->net_salary, 0, ',', '.') }}</span></div>
                    <p class="text-[10px] text-slate-400 mt-2">*Jika nominal di atas keliru, silakan hapus draft ini dan
                        kalkulasi ulang setelah mengedit data bonus/karyawan.</p>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Catatan
                        Tambahan</label>
                    <textarea name="notes" rows="3"
                        class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs focus:outline-none focus:border-[#FF6B00]">{{ old('notes', $payroll->notes) }}</textarea>
                </div>

                <div class="pt-4 flex justify-end space-x-3">
                    <!-- Tombol Batal membawa parameter filter -->
                    <a href="{{ route('payrolls.index', ['month' => $payroll->month_year, 'payroll_type' => $payroll->payroll_type]) }}"
                        class="px-4 py-2.5 bg-slate-100 text-slate-600 font-bold rounded-xl text-xs">Batal</a>
                    <button type="submit"
                        class="px-5 py-2.5 bg-[#FF6B00] hover:bg-[#e66000] text-white font-bold rounded-xl text-xs shadow-lg shadow-orange-500/20 transition">
                        Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
