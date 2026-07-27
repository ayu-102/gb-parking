<x-app-layout>
    <div class="max-w-2xl mx-auto space-y-6">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-xl font-bold text-slate-800 tracking-tight">Catat Absensi Karyawan</h1>
                <p class="text-xs text-slate-500 mt-1">Input data kehadiran manual oleh Admin/HRD.</p>
            </div>
            <a href="{{ route('attendances.index') }}"
                class="px-3.5 py-2 bg-slate-100 text-slate-600 font-bold rounded-xl text-xs hover:bg-slate-200 transition">
                <i class="fa-solid fa-arrow-left mr-1"></i> Kembali
            </a>
        </div>

        <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-6">
            <form action="{{ route('attendances.store') }}" method="POST" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Pilih
                        Karyawan</label>
                    <select name="employee_id" required
                        class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs focus:outline-none focus:border-[#FF6B00] font-medium bg-white">
                        <option value="">-- Pilih Karyawan --</option>
                        @foreach ($employees as $emp)
                            <option value="{{ $emp->id }}">{{ $emp->name }} (NIK: {{ $emp->nik ?? '-' }})
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label
                            class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Tanggal</label>
                        <input type="date" name="date" value="{{ date('Y-m-d') }}" required
                            class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs focus:outline-none focus:border-[#FF6B00] font-medium">
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Status
                            Kehadiran</label>
                        <select name="status" required
                            class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs focus:outline-none focus:border-[#FF6B00] font-medium bg-white">
                            <option value="present">Hadir (Tepat Waktu)</option>
                            <option value="late">Terlambat</option>
                            <option value="permit">Izin</option>
                            <option value="sick">Sakit</option>
                            <option value="absent">Alpha / Mangkir</option>
                        </select>
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Jam Masuk
                            (Clock In)</label>
                        <input type="time" name="time_in" value="08:00"
                            class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs focus:outline-none focus:border-[#FF6B00] font-mono">
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Jam Keluar
                            (Clock Out)</label>
                        <input type="time" name="time_out" value="17:00"
                            class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs focus:outline-none focus:border-[#FF6B00] font-mono">
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Catatan / Alasan
                        (Opsional)</label>
                    <textarea name="notes" rows="3" placeholder="Contoh: Lampiran surat dokter, alasan terlambat, dll."
                        class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs focus:outline-none focus:border-[#FF6B00]"></textarea>
                </div>

                <div class="pt-4 flex justify-end space-x-3">
                    <a href="{{ route('attendances.index') }}"
                        class="px-4 py-2.5 bg-slate-100 text-slate-600 font-bold rounded-xl text-xs hover:bg-slate-200 transition">Batal</a>
                    <button type="submit"
                        class="px-5 py-2.5 bg-gradient-to-r from-[#FF6B00] to-[#FF4500] hover:from-[#e66000] text-white font-bold rounded-xl text-xs shadow-lg shadow-orange-500/20 transition">
                        Simpan Absensi
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
