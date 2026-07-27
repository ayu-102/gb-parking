<x-app-layout>
    <div class="max-w-2xl mx-auto space-y-6">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-xl font-bold text-slate-800 tracking-tight">Edit Komponen Gaji</h1>
                <p class="text-xs text-slate-500 mt-1">Perbarui konfigurasi komponen gaji.</p>
            </div>
            <a href="{{ route('salary-components.index') }}"
                class="px-3.5 py-2 bg-slate-100 text-slate-600 font-bold rounded-xl text-xs hover:bg-slate-200 transition flex items-center space-x-1">
                <i class="fa-solid fa-arrow-left mr-1"></i> Kembali
            </a>
        </div>

        <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-6">
            <form action="{{ route('salary-components.update', $salaryComponent->id) }}" method="POST" class="space-y-4">
                @csrf
                @method('PUT')
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Kode
                        Komponen</label>
                    <input type="text" name="code" value="{{ old('code', $salaryComponent->code) }}" required
                        class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs focus:outline-none focus:border-[#FF6B00] uppercase font-mono font-semibold">
                    @error('code')
                        <p class="text-rose-500 text-[10px] mt-1 font-semibold">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Nama
                        Komponen</label>
                    <input type="text" name="name" value="{{ old('name', $salaryComponent->name) }}" required
                        class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs focus:outline-none focus:border-[#FF6B00]">
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Tipe
                            Komponen</label>
                        <select name="type" required
                            class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs focus:outline-none focus:border-[#FF6B00] bg-white font-medium">
                            <option value="allowance"
                                {{ old('type', $salaryComponent->type) == 'allowance' ? 'selected' : '' }}>Pendapatan /
                                Tunjangan (+)</option>
                            <option value="deduction"
                                {{ old('type', $salaryComponent->type) == 'deduction' ? 'selected' : '' }}>Potongan (-)
                            </option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Jenis
                            Hitungan</label>
                        <select name="amount_type" required
                            class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs focus:outline-none focus:border-[#FF6B00] bg-white font-medium">
                            <option value="fixed"
                                {{ old('amount_type', $salaryComponent->amount_type) == 'fixed' ? 'selected' : '' }}>
                                Nominal Tetap (Rp)</option>
                            <option value="percentage"
                                {{ old('amount_type', $salaryComponent->amount_type) == 'percentage' ? 'selected' : '' }}>
                                Persentase (%)</option>
                        </select>
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Nilai / Nominal
                        Default</label>
                    <input type="number" step="0.01" name="amount"
                        value="{{ old('amount', $salaryComponent->amount) }}" required
                        class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs focus:outline-none focus:border-[#FF6B00] font-bold text-slate-800">
                </div>

                <div class="pt-4 flex justify-end space-x-3">
                    <a href="{{ route('salary-components.index') }}"
                        class="px-4 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-600 font-bold rounded-xl text-xs transition">Batal</a>
                    <button type="submit"
                        class="px-5 py-2.5 bg-gradient-to-r from-[#FF6B00] to-[#FF4500] hover:from-[#e66000] hover:to-[#e03d00] text-white font-bold rounded-xl text-xs shadow-lg shadow-orange-500/20 transition">
                        Perbarui Komponen
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
