<x-app-layout>
    <div class="max-w-xl mx-auto space-y-6">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-slate-800 tracking-tight">Tambah Jabatan Baru</h1>
                <p class="text-xs text-slate-500 mt-1">Masukkan informasi posisi/jabatan baru di GB Parking.</p>
            </div>
            <a href="{{ route('positions.index') }}"
                class="px-3.5 py-2 border border-slate-200 text-slate-600 hover:bg-slate-50 font-bold rounded-xl text-xs transition flex items-center space-x-1">
                <i class="fa-solid fa-arrow-left text-xs"></i>
                <span>Kembali</span>
            </a>
        </div>

        <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-6">
            <form action="{{ route('positions.store') }}" method="POST" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-xs font-bold text-slate-600 uppercase tracking-wider mb-1">Nama Jabatan
                        <span class="text-rose-500">*</span></label>
                    <input type="text" name="name" value="{{ old('name') }}" required
                        placeholder="Contoh: Petugas Kasir Parkir / Supervisor Ops"
                        class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs focus:outline-none focus:border-[#FF6B00] @error('name') border-red-500 @enderror">
                    @error('name')
                        <p class="text-red-500 text-[10px] mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-600 uppercase tracking-wider mb-1">Departemen
                        Naungan (Opsional)</label>
                    <select name="department_id"
                        class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs focus:outline-none focus:border-[#FF6B00] bg-white">
                        <option value="">-- Pilih Departemen --</option>
                        @foreach ($departments as $dept)
                            <option value="{{ $dept->id }}"
                                {{ old('department_id') == $dept->id ? 'selected' : '' }}>
                                {{ $dept->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="pt-4 flex justify-end space-x-3 border-t border-slate-100 mt-6">
                    <a href="{{ route('positions.index') }}"
                        class="px-5 py-2.5 border border-slate-200 text-slate-600 font-bold text-xs rounded-xl hover:bg-slate-50 transition">Batal</a>
                    <button type="submit"
                        class="px-5 py-2.5 bg-[#FF6B00] hover:bg-[#e66000] text-white font-bold text-xs rounded-xl shadow-lg shadow-orange-500/20 transition">
                        Simpan Jabatan
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
