<x-app-layout>
    <div class="max-w-2xl mx-auto space-y-6">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-xl font-bold text-slate-800">Edit Jadwal Shift</h1>
                <p class="text-xs text-slate-500 mt-1">Perbarui plotting shift kerja karyawan.</p>
            </div>
            <a href="{{ route('employee-shifts.index') }}"
                class="px-3.5 py-2 bg-slate-100 text-slate-600 font-bold rounded-xl text-xs hover:bg-slate-200 transition">
                <i class="fa-solid fa-arrow-left mr-1"></i> Kembali
            </a>
        </div>

        <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-6">
            <form action="{{ route('employee-shifts.update', $employeeShift->id) }}" method="POST" class="space-y-4">
                @csrf
                @method('PUT')
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Karyawan</label>
                    <select name="employee_id" required
                        class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs focus:outline-none focus:border-[#FF6B00]">
                        @foreach ($employees as $emp)
                            <option value="{{ $emp->id }}"
                                {{ $employeeShift->employee_id == $emp->id ? 'selected' : '' }}>{{ $emp->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label
                            class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Tanggal</label>
                        <input type="date" name="date" value="{{ old('date', $employeeShift->date) }}" required
                            class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs focus:outline-none focus:border-[#FF6B00]">
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Template
                            Shift</label>
                        <select name="shift_template_id" required
                            class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs focus:outline-none focus:border-[#FF6B00]">
                            @foreach ($shiftTemplates as $shift)
                                <option value="{{ $shift->id }}"
                                    {{ $employeeShift->shift_template_id == $shift->id ? 'selected' : '' }}>
                                    {{ $shift->name }} ({{ substr($shift->start_time, 0, 5) }} -
                                    {{ substr($shift->end_time, 0, 5) }})
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Catatan
                        (Opsional)</label>
                    <textarea name="notes" rows="3"
                        class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs focus:outline-none focus:border-[#FF6B00]">{{ old('notes', $employeeShift->notes) }}</textarea>
                </div>

                <div class="pt-4 flex justify-end space-x-3">
                    <a href="{{ route('employee-shifts.index') }}"
                        class="px-4 py-2.5 bg-slate-100 text-slate-600 font-bold rounded-xl text-xs">Batal</a>
                    <button type="submit"
                        class="px-5 py-2.5 bg-[#FF6B00] hover:bg-[#e66000] text-white font-bold rounded-xl text-xs shadow-lg shadow-orange-500/20 transition">
                        Perbarui Jadwal
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
