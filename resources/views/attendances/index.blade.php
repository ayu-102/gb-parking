<x-app-layout>
    <div class="space-y-6">

        <!-- HEADER PAGE -->
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div>
                <h1 class="text-2xl font-bold text-slate-800 tracking-tight">Data Absensi Karyawan</h1>
                <p class="text-xs text-slate-500 mt-1">Monitor dan rekap absensi harian & histori karyawan.</p>
            </div>
            <div>
                <a href="{{ route('attendances.create') }}"
                    class="px-4 py-2.5 bg-gradient-to-r from-[#FF6B00] to-[#FF4500] hover:from-[#e66000] hover:to-[#e03d00] text-white font-bold rounded-xl text-xs shadow-lg shadow-orange-500/20 flex items-center space-x-2 transition transform active:scale-95">
                    <i class="fa-solid fa-plus text-xs"></i>
                    <span>Catat Absensi</span>
                </a>
            </div>
        </div>

        <!-- NOTIFIKASI SUKSES -->
        @if (session('success'))
            <div
                class="p-4 bg-emerald-50 border border-emerald-200 text-emerald-700 text-xs font-semibold rounded-2xl flex items-center space-x-2 shadow-sm">
                <i class="fa-solid fa-circle-check text-base text-emerald-500"></i>
                <span>{{ session('success') }}</span>
            </div>
        @endif

        <!-- 4 STATISTIK CARDS -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
            <!-- Card Hadir -->
            <div class="bg-white p-5 rounded-2xl border border-slate-100 shadow-sm flex items-center justify-between">
                <div>
                    <p class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">Hadir Tepat Waktu</p>
                    <h3 class="text-2xl font-extrabold text-emerald-600 mt-1">{{ $totalHadir }} <span
                            class="text-xs font-medium text-slate-400">Orang</span></h3>
                    <span class="text-[10px] text-slate-400 font-medium">
                        Periode Dipilih
                    </span>
                </div>
                <div
                    class="w-12 h-12 bg-emerald-50 rounded-2xl flex items-center justify-center text-emerald-500 font-bold text-lg">
                    <i class="fa-solid fa-user-check"></i>
                </div>
            </div>

            <!-- Terlambat -->
            <div class="bg-white p-5 rounded-2xl border border-slate-100 shadow-sm flex items-center justify-between">
                <div>
                    <p class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">Terlambat</p>
                    <h3 class="text-2xl font-extrabold text-amber-500 mt-1">{{ $totalTerlambat }} <span
                            class="text-xs font-medium text-slate-400">Orang</span></h3>
                    <span class="text-[10px] text-slate-400 font-medium">Perlu perhatian HRD</span>
                </div>
                <div
                    class="w-12 h-12 bg-amber-50 rounded-2xl flex items-center justify-center text-amber-500 font-bold text-lg">
                    <i class="fa-solid fa-clock font-bold"></i>
                </div>
            </div>

            <!-- Izin / Sakit -->
            <div class="bg-white p-5 rounded-2xl border border-slate-100 shadow-sm flex items-center justify-between">
                <div>
                    <p class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">Izin & Sakit</p>
                    <h3 class="text-2xl font-extrabold text-blue-600 mt-1">{{ $totalIzinSakit }} <span
                            class="text-xs font-medium text-slate-400">Orang</span></h3>
                    <span class="text-[10px] text-slate-400 font-medium">Ada surat/keterangan</span>
                </div>
                <div
                    class="w-12 h-12 bg-blue-50 rounded-2xl flex items-center justify-center text-blue-500 font-bold text-lg">
                    <i class="fa-solid fa-envelope-open-text"></i>
                </div>
            </div>

            <!-- Alpha / Mangkir -->
            <div class="bg-white p-5 rounded-2xl border border-slate-100 shadow-sm flex items-center justify-between">
                <div>
                    <p class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">Alpha / Tanpa Ket.</p>
                    <h3 class="text-2xl font-extrabold text-rose-600 mt-1">{{ $totalAlpha }} <span
                            class="text-xs font-medium text-slate-400">Orang</span></h3>
                    <span class="text-[10px] text-slate-400 font-medium">Tidak ada kabar</span>
                </div>
                <div
                    class="w-12 h-12 bg-rose-50 rounded-2xl flex items-center justify-center text-rose-500 font-bold text-lg">
                    <i class="fa-solid fa-user-xmark"></i>
                </div>
            </div>
        </div>

        <!-- TABEL ABSENSI -->
        <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">

            <!-- FILTER TOOLBAR (RANGE TANGGAL) -->
            <div class="p-4 border-b border-slate-100 bg-slate-50/50">
                <form method="GET" action="{{ route('attendances.index') }}"
                    class="flex flex-col lg:flex-row items-center gap-3">

                    <!-- Search Input -->
                    <div class="relative w-full lg:w-64">
                        <input type="text" name="search" value="{{ request('search') }}"
                            placeholder="Cari Nama / NIK Karyawan..."
                            class="w-full pl-9 pr-3.5 py-2 rounded-xl border border-slate-200 text-xs focus:outline-none focus:border-[#FF6B00] bg-white">
                        <i class="fa-solid fa-magnifying-glass absolute left-3.5 top-2.5 text-slate-400 text-xs"></i>
                    </div>

                    <!-- Range Tanggal Mulai -->
                    <div class="w-full lg:w-auto flex items-center space-x-2">
                        <span class="text-xs text-slate-400 font-medium">Dari:</span>
                        <input type="date" name="start_date" value="{{ $startDate }}"
                            class="w-full px-3 py-2 rounded-xl border border-slate-200 text-xs text-slate-700 focus:outline-none focus:border-[#FF6B00] bg-white font-medium">
                    </div>

                    <!-- Range Tanggal Selesai -->
                    <div class="w-full lg:w-auto flex items-center space-x-2">
                        <span class="text-xs text-slate-400 font-medium">s/d:</span>
                        <input type="date" name="end_date" value="{{ $endDate }}"
                            class="w-full px-3 py-2 rounded-xl border border-slate-200 text-xs text-slate-700 focus:outline-none focus:border-[#FF6B00] bg-white font-medium">
                    </div>

                    <!-- Status Filter -->
                    <div class="w-full lg:w-40">
                        <select name="status" onchange="this.form.submit()"
                            class="w-full px-3 py-2 rounded-xl border border-slate-200 text-xs focus:outline-none focus:border-[#FF6B00] bg-white font-medium text-slate-700">
                            <option value="Semua">Semua Status</option>
                            <option value="Hadir" {{ request('status') == 'Hadir' ? 'selected' : '' }}>Hadir</option>
                            <option value="Terlambat" {{ request('status') == 'Terlambat' ? 'selected' : '' }}>
                                Terlambat</option>
                            <option value="Izin" {{ request('status') == 'Izin' ? 'selected' : '' }}>Izin</option>
                            <option value="Sakit" {{ request('status') == 'Sakit' ? 'selected' : '' }}>Sakit</option>
                            <option value="Alpha" {{ request('status') == 'Alpha' ? 'selected' : '' }}>Alpha</option>
                        </select>
                    </div>

                    <button type="submit"
                        class="w-full lg:w-auto px-4 py-2 bg-slate-800 hover:bg-slate-900 text-white font-bold rounded-xl text-xs transition">
                        Filter
                    </button>

                    @if (request()->filled('search') || request()->filled('status') || request()->filled('start_date'))
                        <a href="{{ route('attendances.index') }}"
                            class="text-xs font-bold text-rose-500 hover:underline px-1 whitespace-nowrap">Reset
                            Filter</a>
                    @endif
                </form>
            </div>

            <!-- TABLE CONTENT -->
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs">
                    <thead
                        class="bg-slate-50 text-slate-400 font-bold border-b border-slate-100 uppercase tracking-wider">
                        <tr>
                            <th class="p-4">Tanggal</th>
                            <th class="p-4">Karyawan</th>
                            <th class="p-4 text-center">Jam Masuk</th>
                            <th class="p-4 text-center">Jam Keluar</th>
                            <th class="p-4">Status</th>
                            <th class="p-4">Catatan / Alasan</th>
                            <th class="p-4 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 text-slate-700">
                        @forelse ($attendances as $item)
                            <tr class="hover:bg-slate-50/80 transition">
                                <!-- Tanggal -->
                                <td class="p-4 font-bold text-slate-800 whitespace-nowrap">
                                    {{ \Carbon\Carbon::parse($item->date)->format('d M Y') }}
                                </td>

                                <!-- Karyawan Info + Foto Selfie Absen -->
                                <td class="p-4 whitespace-nowrap">
                                    <div class="flex items-center space-x-3">
                                        @if (!empty($item->photo_in))
                                            <a href="{{ asset('storage/' . $item->photo_in) }}" target="_blank"
                                                title="Klik untuk lihat foto selfie">
                                                <img src="{{ asset('storage/' . $item->photo_in) }}" alt="Selfie"
                                                    class="w-9 h-9 rounded-full object-cover border-2 border-[#FF6B00] shadow-sm hover:scale-110 transition">
                                            </a>
                                        @else
                                            <div
                                                class="w-9 h-9 rounded-full bg-slate-100 border border-slate-200 flex items-center justify-center font-bold text-slate-600 text-xs uppercase">
                                                {{ substr($item->employee->name ?? 'K', 0, 2) }}
                                            </div>
                                        @endif

                                        <div>
                                            <p class="font-bold text-slate-800">{{ $item->employee->name ?? '-' }}</p>
                                            <p class="text-[10px] text-slate-400 font-mono">
                                                {{ $item->employee->nik ?? '-' }}
                                            </p>
                                        </div>
                                    </div>
                                </td>

                                <!-- Jam Masuk -->
                                <td class="p-4 text-center font-mono font-bold whitespace-nowrap">
                                    @if ($item->time_in)
                                        <span
                                            class="px-2.5 py-1 bg-slate-100 rounded-lg text-slate-800 border border-slate-200">
                                            {{ substr($item->time_in, 0, 5) }}
                                        </span>
                                    @else
                                        <span class="text-slate-300">-</span>
                                    @endif
                                </td>

                                <!-- Jam Keluar -->
                                <td class="p-4 text-center font-mono font-bold whitespace-nowrap">
                                    @if ($item->time_out)
                                        <span
                                            class="px-2.5 py-1 bg-slate-100 rounded-lg text-slate-800 border border-slate-200">
                                            {{ substr($item->time_out, 0, 5) }}
                                        </span>
                                    @else
                                        <span class="text-slate-300">-</span>
                                    @endif
                                </td>

                                <!-- Status Badge -->
                                <td class="p-4 whitespace-nowrap">
                                    @if ($item->status == 'Hadir' || $item->status == 'present')
                                        <span
                                            class="px-2.5 py-1 bg-emerald-50 text-emerald-700 border border-emerald-200 font-bold rounded-lg text-[10px] inline-flex items-center gap-1">
                                            <i class="fa-solid fa-circle-check text-[9px]"></i> Hadir
                                        </span>
                                    @elseif($item->status == 'Terlambat' || $item->status == 'late')
                                        <span
                                            class="px-2.5 py-1 bg-amber-50 text-amber-700 border border-amber-200 font-bold rounded-lg text-[10px] inline-flex items-center gap-1">
                                            <i class="fa-solid fa-clock text-[9px]"></i> Terlambat
                                        </span>
                                    @elseif($item->status == 'Alpha' || $item->status == 'absent')
                                        <span
                                            class="px-2.5 py-1 bg-rose-50 text-rose-700 border border-rose-200 font-bold rounded-lg text-[10px] inline-flex items-center gap-1">
                                            <i class="fa-solid fa-circle-xmark text-[9px]"></i> Alpha
                                        </span>
                                    @elseif($item->status == 'Izin' || $item->status == 'permit')
                                        <span
                                            class="px-2.5 py-1 bg-blue-50 text-blue-700 border border-blue-200 font-bold rounded-lg text-[10px] inline-flex items-center gap-1">
                                            <i class="fa-solid fa-file-signature text-[9px]"></i> Izin
                                        </span>
                                    @else
                                        <span
                                            class="px-2.5 py-1 bg-purple-50 text-purple-700 border border-purple-200 font-bold rounded-lg text-[10px] inline-flex items-center gap-1">
                                            <i class="fa-solid fa-notes-medical text-[9px]"></i> {{ $item->status }}
                                        </span>
                                    @endif
                                </td>

                                <!-- Catatan / Alasan -->
                                <td class="p-4 text-slate-500 max-w-xs truncate">
                                    {{ $item->notes ?? ($item->reason ?? '-') }}
                                </td>

                                <!-- Aksi -->
                                <td class="p-4 text-center space-x-1 whitespace-nowrap">
                                    <a href="{{ route('attendances.edit', $item->id) }}"
                                        class="p-2 text-slate-400 hover:text-amber-600 hover:bg-amber-50 rounded-xl transition inline-block">
                                        <i class="fa-solid fa-pen text-xs"></i>
                                    </a>
                                    <form action="{{ route('attendances.destroy', $item->id) }}" method="POST"
                                        class="inline-block" onsubmit="return confirm('Hapus absensi ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                            class="p-2 text-slate-400 hover:text-rose-600 hover:bg-rose-50 rounded-xl transition">
                                            <i class="fa-solid fa-trash text-xs"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="p-12 text-center text-slate-400">
                                    <div
                                        class="w-16 h-16 mx-auto bg-slate-50 rounded-full flex items-center justify-center mb-3 text-slate-300">
                                        <i class="fa-solid fa-calendar-xmark text-2xl"></i>
                                    </div>
                                    <p class="font-bold text-slate-600 text-sm">Tidak Ada Data Absensi</p>
                                    <p class="text-xs text-slate-400 mt-1">Gunakan filter rentang tanggal lain atau
                                        klik 'Catat Absensi'.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if (method_exists($attendances, 'links'))
                <div class="p-4 border-t border-slate-100 bg-slate-50/50">
                    {{ $attendances->links() }}
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
