<x-app-layout>
    <div class="space-y-6">
        <!-- HEADER PAGE -->
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div>
                <h1 class="text-2xl font-bold text-slate-800 tracking-tight">Master Data Lokasi</h1>
                <p class="text-xs text-slate-500 mt-1">Kelola daftar area, cabang, dan jangkauan radius absensi GB
                    Parking.</p>
            </div>
            <div>
                <a href="{{ route('locations.create') }}"
                    class="px-4 py-2.5 bg-[#FF6B00] hover:bg-[#e66000] text-white font-bold rounded-xl text-xs shadow-lg shadow-orange-500/20 flex items-center space-x-2 transition">
                    <i class="fa-solid fa-plus"></i>
                    <span>Tambah Lokasi</span>
                </a>
            </div>
        </div>

        <!-- NOTIFIKASI SUKSES -->
        @if (session('success'))
            <div
                class="p-4 bg-emerald-50 border border-emerald-200 text-emerald-700 text-xs font-semibold rounded-2xl flex items-center space-x-2 shadow-sm">
                <i class="fa-solid fa-circle-check text-base text-emerald-600"></i>
                <span>{{ session('success') }}</span>
            </div>
        @endif

        <!-- STAT CARDS -->
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
            <div class="bg-white p-4 rounded-2xl border border-slate-100 shadow-sm flex items-center space-x-3.5">
                <div
                    class="w-11 h-11 rounded-xl bg-orange-50 text-[#FF6B00] flex items-center justify-center font-bold text-base shrink-0">
                    <i class="fa-solid fa-location-dot"></i>
                </div>
                <div>
                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Total Lokasi/Cabang</p>
                    <h4 class="text-base font-extrabold text-slate-800 tracking-tight">
                        {{ number_format($totalLocations) }} Titik</h4>
                </div>
            </div>

            <div class="bg-white p-4 rounded-2xl border border-slate-100 shadow-sm flex items-center space-x-3.5">
                <div
                    class="w-11 h-11 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center font-bold text-base shrink-0">
                    <i class="fa-solid fa-city"></i>
                </div>
                <div>
                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Kota Terjangkau</p>
                    <h4 class="text-base font-extrabold text-slate-800 tracking-tight">{{ number_format($totalCities) }}
                        Wilayah</h4>
                </div>
            </div>

            <div class="bg-white p-4 rounded-2xl border border-slate-100 shadow-sm flex items-center space-x-3.5">
                <div
                    class="w-11 h-11 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center font-bold text-base shrink-0">
                    <i class="fa-solid fa-users"></i>
                </div>
                <div>
                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Total Karyawan</p>
                    <h4 class="text-base font-extrabold text-slate-800 tracking-tight">
                        {{ number_format($totalEmployees) }} Personel</h4>
                </div>
            </div>
        </div>

        <!-- TABEL DATA LOKASI -->
        <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
            <div class="p-4 border-b border-slate-100 flex items-center justify-between gap-4 bg-slate-50/50">
                <form method="GET" action="{{ route('locations.index') }}" class="relative w-full sm:w-80">
                    <input type="text" name="search" value="{{ request('search') }}"
                        placeholder="Cari Nama Lokasi atau Kota..."
                        class="w-full pl-9 pr-3.5 py-2 rounded-xl border border-slate-200 text-xs focus:outline-none focus:border-[#FF6B00] bg-white">
                    <i class="fa-solid fa-magnifying-glass absolute left-3 top-2.5 text-slate-400 text-xs"></i>
                </form>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs">
                    <thead
                        class="bg-slate-50 text-slate-400 font-bold border-b border-slate-100 uppercase tracking-wider">
                        <tr>
                            <th class="p-4">Nama Lokasi / Cabang</th>
                            <th class="p-4">Kota</th>
                            <th class="p-4">Radius Absensi</th>
                            <th class="p-4">Personel</th>
                            <th class="p-4">Alamat Lengkap</th>
                            <th class="p-4 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 text-slate-700">
                        @forelse ($locations as $location)
                            <tr class="hover:bg-slate-50/80 transition">
                                <td class="p-4">
                                    <div class="flex items-center space-x-3">
                                        <div
                                            class="w-8 h-8 rounded-lg bg-orange-50 text-[#FF6B00] font-bold flex items-center justify-center text-xs shrink-0">
                                            <i class="fa-solid fa-map-pin"></i>
                                        </div>
                                        <span class="font-bold text-slate-800 text-xs">{{ $location->name }}</span>
                                    </div>
                                </td>
                                <td class="p-4 font-semibold text-slate-600">
                                    @if ($location->city)
                                        <span class="px-2.5 py-1 bg-slate-100 text-slate-700 rounded-lg text-[10px]">
                                            <i
                                                class="fa-solid fa-city text-[9px] mr-1 text-slate-400"></i>{{ $location->city }}
                                        </span>
                                    @else
                                        <span class="text-slate-400 text-[11px]">-</span>
                                    @endif
                                </td>
                                <td class="p-4">
                                    <span class="px-2.5 py-1 bg-blue-50 text-blue-600 font-bold rounded-lg text-[10px]">
                                        <i class="fa-solid fa-location-crosshairs mr-1"></i>{{ $location->radius }}
                                        Meter
                                    </span>
                                </td>
                                <td class="p-4 font-semibold text-slate-600">
                                    <span
                                        class="px-2 py-1 bg-slate-100 text-slate-700 rounded-md text-[10px] font-mono">
                                        {{ $location->employees_count ?? 0 }} Orang
                                    </span>
                                </td>
                                <td class="p-4 text-slate-500 max-w-xs truncate" title="{{ $location->address }}">
                                    {{ $location->address ?? '-' }}
                                </td>
                                <td class="p-4 text-center">
                                    <div class="flex items-center justify-center space-x-1">
                                        <a href="{{ route('locations.edit', $location->id) }}"
                                            class="w-7 h-7 rounded-lg text-amber-500 hover:bg-amber-50 flex items-center justify-center transition"
                                            title="Edit Lokasi">
                                            <i class="fa-solid fa-pen text-xs"></i>
                                        </a>
                                        <form action="{{ route('locations.destroy', $location->id) }}" method="POST"
                                            class="inline-block"
                                            onsubmit="return confirm('Yakin ingin menghapus lokasi {{ $location->name }}?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                class="w-7 h-7 rounded-lg text-rose-500 hover:bg-rose-50 flex items-center justify-center transition"
                                                title="Hapus Lokasi">
                                                <i class="fa-solid fa-trash text-xs"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="p-12 text-center text-slate-400">
                                    <div
                                        class="w-16 h-16 mx-auto bg-slate-50 rounded-full flex items-center justify-center mb-3 text-slate-300">
                                        <i class="fa-solid fa-location-dot text-2xl"></i>
                                    </div>
                                    <p class="font-bold text-slate-600 text-sm">Belum Ada Data Lokasi</p>
                                    <p class="text-xs text-slate-400 mt-1">Klik tombol 'Tambah Lokasi' di atas untuk
                                        memasukkan data lokasi baru.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if (method_exists($locations, 'links'))
                <div class="p-4 border-t border-slate-100 bg-slate-50/50">
                    {{ $locations->links() }}
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
