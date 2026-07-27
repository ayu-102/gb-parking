<x-app-layout>
    <div class="max-w-xl mx-auto space-y-6">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-slate-800 tracking-tight">Edit Data Departemen</h1>
                <p class="text-xs text-slate-500 mt-1">Perbarui informasi kode atau nama departemen.</p>
            </div>
            <a href="{{ route('departments.index') }}"
                class="px-3.5 py-2 border border-slate-200 text-slate-600 hover:bg-slate-50 font-bold rounded-xl text-xs transition flex items-center space-x-1">
                <i class="fa-solid fa-arrow-left text-xs"></i>
                <span>Kembali</span>
            </a>
        </div>

        <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-6">
            <form action="{{ route('departments.update', $department->id) }}" method="POST" class="space-y-4">
                @csrf
                @method('PUT')

                <div>
                    <label for="code" class="block text-xs font-bold text-slate-600 uppercase tracking-wider mb-1">
                        Kode Departemen <span class="text-rose-500">*</span>
                    </label>
                    <input type="text" name="code" id="code" value="{{ old('code', $department->code) }}"
                        required placeholder="Contoh: HRD, IT, OPS"
                        class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs focus:outline-none focus:border-[#FF6B00] uppercase font-mono @error('code') border-red-500 @enderror">
                    @error('code')
                        <p class="text-red-500 text-[10px] mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="name" class="block text-xs font-bold text-slate-600 uppercase tracking-wider mb-1">
                        Nama Departemen <span class="text-rose-500">*</span>
                    </label>
                    <input type="text" name="name" id="name" value="{{ old('name', $department->name) }}"
                        required placeholder="Contoh: Human Resources Development"
                        class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs focus:outline-none focus:border-[#FF6B00] @error('name') border-red-500 @enderror">
                    @error('name')
                        <p class="text-red-500 text-[10px] mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="pt-4 flex justify-end space-x-3 border-t border-slate-100 mt-6">
                    <a href="{{ route('departments.index') }}"
                        class="px-5 py-2.5 border border-slate-200 text-slate-600 font-bold text-xs rounded-xl hover:bg-slate-50 transition">Batal</a>
                    <button type="submit"
                        class="px-5 py-2.5 bg-[#FF6B00] hover:bg-[#e66000] text-white font-bold text-xs rounded-xl shadow-lg shadow-orange-500/20 transition">
                        Perbarui Data
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
