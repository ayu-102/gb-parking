<x-app-layout>
    <div class="max-w-3xl mx-auto space-y-6">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-xl font-bold text-slate-800">Plotting Jadwal Shift Karyawan</h1>
                <p class="text-xs text-slate-500 mt-1">Atur jadwal shift sekaligus untuk banyak karyawan dan rentang
                    tanggal.</p>
            </div>
            <a href="{{ route('employee-shifts.index') }}"
                class="px-3.5 py-2 bg-slate-100 text-slate-600 font-bold rounded-xl text-xs hover:bg-slate-200 transition">
                <i class="fa-solid fa-arrow-left mr-1"></i> Kembali
            </a>
        </div>

        <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-6">
            <form action="{{ route('employee-shifts.store') }}" method="POST" class="space-y-5">
                @csrf

                <!-- PILIH BANYAK KARYAWAN -->
                <div>
                    <div class="flex items-center justify-between mb-2">
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider">
                            Pilih Karyawan (Bisa Banyak)
                        </label>
                        <button type="button" id="select-all-btn" onclick="toggleSelectAll()"
                            class="text-[11px] text-[#FF6B00] font-bold hover:underline">
                            Pilih Semua
                        </button>
                    </div>

                    <div
                        class="max-h-48 overflow-y-auto p-3 border border-slate-200 rounded-xl space-y-2 bg-slate-50/50">
                        @foreach ($employees as $emp)
                            <label
                                class="flex items-center space-x-3 p-2 bg-white rounded-lg border border-slate-100 hover:border-orange-300 transition cursor-pointer">
                                <input type="checkbox" name="employee_ids[]" value="{{ $emp->id }}"
                                    class="employee-checkbox rounded text-[#FF6B00] focus:ring-[#FF6B00]">
                                <span class="text-xs font-semibold text-slate-700">
                                    {{ $emp->name }} <span
                                        class="text-slate-400 font-normal">({{ $emp->nik ?? '-' }})</span>
                                </span>
                            </label>
                        @endforeach
                    </div>
                    @error('employee_ids')
                        <p class="text-rose-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- RENTANG TANGGAL -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Dari
                            Tanggal</label>
                        <input type="date" name="start_date" value="{{ date('Y-m-d') }}" required
                            class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs focus:outline-none focus:border-[#FF6B00] font-medium">
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Sampai
                            Tanggal</label>
                        <input type="date" name="end_date" value="{{ date('Y-m-d') }}" required
                            class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs focus:outline-none focus:border-[#FF6B00] font-medium">
                    </div>
                </div>

                <!-- TEMPLATE SHIFT -->
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Template
                        Shift</label>
                    <select name="shift_template_id" required
                        class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs focus:outline-none focus:border-[#FF6B00] bg-white font-medium">
                        <option value="">-- Pilih Shift --</option>
                        @foreach ($shiftTemplates as $shift)
                            <option value="{{ $shift->id }}">{{ $shift->name }}
                                ({{ substr($shift->start_time, 0, 5) }} - {{ substr($shift->end_time, 0, 5) }})
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- CATATAN -->
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Catatan
                        Penugasan (Opsional)</label>
                    <textarea name="notes" rows="2" placeholder="Contoh: Penugasan Pos Utama / Shift Pengganti..."
                        class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs focus:outline-none focus:border-[#FF6B00]"></textarea>
                </div>

                <!-- BUTTONS -->
                <div class="pt-4 flex justify-end space-x-3 border-t border-slate-100">
                    <a href="{{ route('employee-shifts.index') }}"
                        class="px-4 py-2.5 bg-slate-100 text-slate-600 font-bold rounded-xl text-xs hover:bg-slate-200 transition">Batal</a>
                    <button type="submit"
                        class="px-5 py-2.5 bg-[#FF6B00] hover:bg-[#e66000] text-white font-bold rounded-xl text-xs shadow-lg shadow-orange-500/20 transition">
                        Simpan Semua Jadwal
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- SCRIPT TOGLE SELECT ALL -->
    <script>
        let isAllSelected = false;

        function toggleSelectAll() {
            const checkboxes = document.querySelectorAll('.employee-checkbox');
            const btn = document.getElementById('select-all-btn');
            isAllSelected = !isAllSelected;

            checkboxes.forEach(cb => cb.checked = isAllSelected);
            btn.innerText = isAllSelected ? 'Batalkan Semua' : 'Pilih Semua';
        }
    </script>
</x-app-layout>
