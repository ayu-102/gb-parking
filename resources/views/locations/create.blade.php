<x-app-layout>
    <div class="max-w-xl mx-auto space-y-6">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-slate-800 tracking-tight">Tambah Lokasi Baru</h1>
                <p class="text-xs text-slate-500 mt-1">Masukkan data lokasi penempatan tugas GB Parking.</p>
            </div>
            <a href="{{ route('locations.index') }}"
                class="px-3.5 py-2 border border-slate-200 text-slate-600 hover:bg-slate-50 font-bold rounded-xl text-xs transition flex items-center space-x-1">
                <i class="fa-solid fa-arrow-left text-xs"></i>
                <span>Kembali</span>
            </a>
        </div>

        <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-6">
            <form action="{{ route('locations.store') }}" method="POST" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-xs font-bold text-slate-600 uppercase tracking-wider mb-1">
                        Nama Lokasi / Cabang <span class="text-rose-500">*</span>
                    </label>
                    <input type="text" name="name" value="{{ old('name') }}" required
                        placeholder="Contoh: Mall Taman Anggrek"
                        class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs focus:outline-none focus:border-[#FF6B00] @error('name') border-red-500 @enderror">
                    @error('name')
                        <p class="text-red-500 text-[10px] mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-600 uppercase tracking-wider mb-1">Kota
                            (Opsional)</label>
                        <input type="text" name="city" value="{{ old('city') }}"
                            placeholder="Contoh: Jakarta Barat"
                            class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs focus:outline-none focus:border-[#FF6B00]">
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-600 uppercase tracking-wider mb-1">
                            Radius Absensi (Meter) <span class="text-rose-500">*</span>
                        </label>
                        <input type="number" name="radius" value="{{ old('radius', 100) }}" required placeholder="100"
                            class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs focus:outline-none focus:border-[#FF6B00]">
                    </div>
                </div>

                <!-- SECTION KOORDINAT GOOGLE MAPS (OPSI C) -->
                <div class="p-3.5 bg-orange-50/50 rounded-xl border border-orange-100 space-y-3">
                    <div class="flex items-center justify-between">
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider">
                            <i class="fa-solid fa-map-pin text-[#FF6B00] mr-1"></i> Titik Koordinat Google Maps
                        </label>
                        <span class="text-[10px] text-slate-400">Klik kanan lokasi di G-Maps lalu salin koordinat</span>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                        <div>
                            <label class="block text-[11px] font-semibold text-slate-500 mb-1">Latitude</label>
                            <input type="text" name="latitude" value="{{ old('latitude') }}"
                                placeholder="Contoh: -6.175392"
                                class="w-full px-3 py-2 rounded-lg border border-slate-200 text-xs focus:outline-none focus:border-[#FF6B00] bg-white">
                        </div>
                        <div>
                            <label class="block text-[11px] font-semibold text-slate-500 mb-1">Longitude</label>
                            <input type="text" name="longitude" value="{{ old('longitude') }}"
                                placeholder="Contoh: 106.827153"
                                class="w-full px-3 py-2 rounded-lg border border-slate-200 text-xs focus:outline-none focus:border-[#FF6B00] bg-white">
                        </div>
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-600 uppercase tracking-wider mb-1">Alamat Lengkap
                        (Opsional)</label>
                    <textarea name="address" rows="3" placeholder="Jl. Letjen S. Parman No.28, Jakarta Barat..."
                        class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs focus:outline-none focus:border-[#FF6B00]">{{ old('address') }}</textarea>
                </div>

                <div class="pt-4 flex justify-end space-x-3 border-t border-slate-100 mt-6">
                    <a href="{{ route('locations.index') }}"
                        class="px-5 py-2.5 border border-slate-200 text-slate-600 font-bold text-xs rounded-xl hover:bg-slate-50 transition">Batal</a>
                    <button type="submit"
                        class="px-5 py-2.5 bg-[#FF6B00] hover:bg-[#e66000] text-white font-bold text-xs rounded-xl shadow-lg shadow-orange-500/20 transition">
                        Simpan Lokasi
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
