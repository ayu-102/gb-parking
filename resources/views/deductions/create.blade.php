<x-app-layout>
    <div class="max-w-2xl mx-auto space-y-6">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-xl font-bold text-slate-800">Tambah Potongan Gaji</h1>
                <p class="text-xs text-slate-500 mt-1">Input potongan untuk karyawan tertentu.</p>
            </div>
            <a href="{{ route('deductions.index') }}"
                class="px-3.5 py-2 bg-slate-100 text-slate-600 font-bold rounded-xl text-xs hover:bg-slate-200 transition">
                <i class="fa-solid fa-arrow-left mr-1"></i> Kembali
            </a>
        </div>

        <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-6">
            <form action="{{ route('deductions.store') }}" method="POST" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Karyawan</label>
                    <select name="employee_id" required
                        class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs focus:outline-none focus:border-[#FF6B00]">
                        <option value="">-- Pilih Karyawan --</option>
                        @foreach ($employees as $emp)
                            <option value="{{ $emp->id }}">{{ $emp->name }} ({{ $emp->nik ?? '-' }})</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Jenis
                        Potongan</label>
                    <input type="text" name="type" placeholder="Contoh: Kasbon, Keterlambatan, Denda Atribut"
                        required
                        class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs focus:outline-none focus:border-[#FF6B00]">
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Jumlah
                            Potongan (Rp)</label>
                        <input type="number" name="amount" placeholder="0" required
                            class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs focus:outline-none focus:border-[#FF6B00]">
                    </div>

                    <div>
                        <label
                            class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Tanggal</label>
                        <input type="date" name="date" value="{{ date('Y-m-d') }}" required
                            class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs focus:outline-none focus:border-[#FF6B00]">
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Keterangan
                        (Opsional)</label>
                    <textarea name="description" rows="3" placeholder="Catatan tambahan..."
                        class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs focus:outline-none focus:border-[#FF6B00]"></textarea>
                </div>

                <div class="pt-4 flex justify-end space-x-3">
                    <a href="{{ route('deductions.index') }}"
                        class="px-4 py-2.5 bg-slate-100 text-slate-600 font-bold rounded-xl text-xs">Batal</a>
                    <button type="submit"
                        class="px-5 py-2.5 bg-[#FF6B00] hover:bg-[#e66000] text-white font-bold rounded-xl text-xs shadow-lg shadow-orange-500/20 transition">
                        Simpan Potongan
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
