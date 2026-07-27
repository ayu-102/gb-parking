<x-app-layout>
    <div class="space-y-6">

        <!-- HEADER HALAMAN -->
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div>
                <h1 class="text-2xl font-bold text-slate-800 tracking-tight">Daftar Potongan Gaji</h1>
                <p class="text-xs text-slate-500 mt-1">Kelola pencatatan potongan insidental karyawan seperti kasbon,
                    denda, atau ganti rugi.</p>
            </div>
            <div>
                <a href="{{ route('deductions.create') }}"
                    class="px-4 py-2.5 bg-gradient-to-r from-[#FF6B00] to-[#FF4500] hover:from-[#e66000] hover:to-[#e03d00] text-white font-bold rounded-xl text-xs shadow-lg shadow-orange-500/20 flex items-center space-x-2 transition transform active:scale-95">
                    <i class="fa-solid fa-plus text-xs"></i>
                    <span>Tambah Potongan</span>
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

        <!-- 4 STATISTIK CARDS (RINGKASAN UTAMA) -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
            <!-- Card 1: Total Potongan -->
            <div class="bg-white p-5 rounded-2xl border border-slate-100 shadow-sm flex items-center justify-between">
                <div>
                    <p class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">Total Potongan (Bulan Ini)
                    </p>
                    <h3 class="text-xl font-extrabold text-slate-800 mt-1">Rp
                        {{ number_format($totalNominal, 0, ',', '.') }}</h3>
                    <span class="text-[10px] text-slate-400 font-medium">Periode {{ date('F Y') }}</span>
                </div>
                <div
                    class="w-12 h-12 bg-emerald-50 rounded-2xl flex items-center justify-center text-emerald-500 font-bold text-lg">
                    <i class="fa-solid fa-wallet"></i>
                </div>
            </div>

            <!-- Card 2: Karyawan Terpotong -->
            <div class="bg-white p-5 rounded-2xl border border-slate-100 shadow-sm flex items-center justify-between">
                <div>
                    <p class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">Karyawan Terpotong</p>
                    <h3 class="text-xl font-extrabold text-slate-800 mt-1">{{ $totalKaryawan }} <span
                            class="text-xs font-medium text-slate-500">Orang</span></h3>
                    <span class="text-[10px] text-slate-400 font-medium">Total individu bulan ini</span>
                </div>
                <div
                    class="w-12 h-12 bg-rose-50 rounded-2xl flex items-center justify-center text-rose-500 font-bold text-lg">
                    <i class="fa-solid fa-users"></i>
                </div>
            </div>

            <!-- Card 3: Total Kasbon -->
            <div class="bg-white p-5 rounded-2xl border border-slate-100 shadow-sm flex items-center justify-between">
                <div>
                    <p class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">Total Kasbon</p>
                    <h3 class="text-xl font-extrabold text-slate-800 mt-1">Rp
                        {{ number_format($totalKasbon, 0, ',', '.') }}</h3>
                    <span class="text-[10px] text-slate-400 font-medium">Pinjaman karyawan</span>
                </div>
                <div
                    class="w-12 h-12 bg-amber-50 rounded-2xl flex items-center justify-center text-amber-500 font-bold text-lg">
                    <i class="fa-solid fa-hand-holding-dollar"></i>
                </div>
            </div>

            <!-- Card 4: Total Denda & Lainnya -->
            <div class="bg-white p-5 rounded-2xl border border-slate-100 shadow-sm flex items-center justify-between">
                <div>
                    <p class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">Denda & Lainnya</p>
                    <h3 class="text-xl font-extrabold text-slate-800 mt-1">Rp
                        {{ number_format($totalDenda, 0, ',', '.') }}</h3>
                    <span class="text-[10px] text-slate-400 font-medium">Pelanggaran / atribut</span>
                </div>
                <div
                    class="w-12 h-12 bg-blue-50 rounded-2xl flex items-center justify-center text-blue-500 font-bold text-lg">
                    <i class="fa-solid fa-circle-exclamation"></i>
                </div>
            </div>
        </div>

        <!-- TABEL DATA UTAMA -->
        <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">

            <!-- FILTER & PENCARIAN -->
            <div
                class="p-4 border-b border-slate-100 flex flex-col md:flex-row md:items-center justify-between gap-4 bg-slate-50/50">
                <form method="GET" action="{{ route('deductions.index') }}"
                    class="flex flex-col sm:flex-row items-center gap-3 w-full md:w-auto">
                    <!-- Input Cari -->
                    <div class="relative w-full sm:w-72">
                        <input type="text" name="search" value="{{ request('search') }}"
                            placeholder="Cari NIK, Nama Karyawan..."
                            class="w-full pl-9 pr-3.5 py-2.5 rounded-xl border border-slate-200 text-xs focus:outline-none focus:border-[#FF6B00] bg-white">
                        <i class="fa-solid fa-magnifying-glass absolute left-3.5 top-3 text-slate-400 text-xs"></i>
                    </div>

                    <!-- Dropdown Filter Jenis Potongan -->
                    <select name="type" onchange="this.form.submit()"
                        class="w-full sm:w-48 px-3 py-2.5 rounded-xl border border-slate-200 text-xs focus:outline-none focus:border-[#FF6B00] bg-white font-medium text-slate-700">
                        <option value="Semua">Semua Jenis Potongan</option>
                        <option value="Kasbon" {{ request('type') == 'Kasbon' ? 'selected' : '' }}>Kasbon</option>
                        <option value="Denda" {{ request('type') == 'Denda' ? 'selected' : '' }}>Denda</option>
                        <option value="Terlambat" {{ request('type') == 'Terlambat' ? 'selected' : '' }}>Terlambat
                        </option>
                        <option value="Lainnya" {{ request('type') == 'Lainnya' ? 'selected' : '' }}>Lainnya</option>
                    </select>

                    @if (request()->filled('search') || request()->filled('type'))
                        <a href="{{ route('deductions.index') }}"
                            class="text-xs font-bold text-rose-500 hover:underline px-2">Reset Filter</a>
                    @endif
                </form>
            </div>

            <!-- TABEL -->
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs">
                    <thead
                        class="bg-slate-50 text-slate-400 font-bold border-b border-slate-100 uppercase tracking-wider">
                        <tr>
                            <th class="p-4">Tanggal</th>
                            <th class="p-4">Karyawan</th>
                            <th class="p-4">Departemen</th>
                            <th class="p-4">Jenis Potongan</th>
                            <th class="p-4">Keterangan</th>
                            <th class="p-4 text-right">Jumlah Potongan</th>
                            <th class="p-4 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 text-slate-700">
                        @forelse ($deductions as $item)
                            <tr class="hover:bg-slate-50/80 transition">
                                <!-- Tanggal -->
                                <td class="p-4 font-semibold text-slate-500 whitespace-nowrap">
                                    <i class="fa-regular fa-calendar mr-1.5 text-slate-400"></i>
                                    {{ \Carbon\Carbon::parse($item->date)->format('d M Y') }}
                                </td>

                                <!-- Karyawan Info -->
                                <td class="p-4 whitespace-nowrap">
                                    <div class="flex items-center space-x-3">
                                        <div
                                            class="w-8 h-8 rounded-full bg-orange-100 text-[#FF6B00] font-extrabold flex items-center justify-center text-xs">
                                            {{ strtoupper(substr($item->employee->name ?? 'K', 0, 1)) }}
                                        </div>
                                        <div>
                                            <div class="font-bold text-slate-900">
                                                {{ $item->employee->name ?? 'Karyawan Dihapus' }}</div>
                                            <div class="text-[10px] text-slate-400 font-mono">
                                                {{ $item->employee->nik ?? '-' }}</div>
                                        </div>
                                    </div>
                                </td>

                                <!-- Departemen -->
                                <td class="p-4 whitespace-nowrap font-medium text-slate-600">
                                    {{ $item->employee->department->name ?? 'Operasional' }}
                                </td>

                                <!-- Jenis Potongan Badge -->
                                <td class="p-4 whitespace-nowrap">
                                    @php
                                        $badgeStyle = 'bg-slate-100 text-slate-600';
                                        $typeLower = strtolower($item->type);
                                        if (str_contains($typeLower, 'kasbon')) {
                                            $badgeStyle = 'bg-amber-50 text-amber-700 border border-amber-200';
                                        } elseif (str_contains($typeLower, 'denda')) {
                                            $badgeStyle = 'bg-rose-50 text-rose-700 border border-rose-200';
                                        } elseif (str_contains($typeLower, 'terlambat')) {
                                            $badgeStyle = 'bg-orange-50 text-orange-700 border border-orange-200';
                                        }
                                    @endphp
                                    <span
                                        class="px-2.5 py-1 font-bold rounded-lg text-[10px] inline-block {{ $badgeStyle }}">
                                        {{ $item->type }}
                                    </span>
                                </td>

                                <!-- Keterangan -->
                                <td class="p-4 text-slate-500 max-w-xs truncate">
                                    {{ $item->description ?? '-' }}
                                </td>

                                <!-- Jumlah Nominal -->
                                <td class="p-4 font-extrabold text-rose-600 text-right whitespace-nowrap">
                                    - Rp {{ number_format($item->amount, 0, ',', '.') }}
                                </td>

                                <!-- Tombol Aksi -->
                                <td class="p-4 text-center space-x-1 whitespace-nowrap">
                                    <a href="{{ route('deductions.edit', $item->id) }}"
                                        class="p-2 text-slate-400 hover:text-amber-600 hover:bg-amber-50 rounded-xl transition inline-block"
                                        title="Edit">
                                        <i class="fa-solid fa-pen text-xs"></i>
                                    </a>
                                    <form action="{{ route('deductions.destroy', $item->id) }}" method="POST"
                                        class="inline-block"
                                        onsubmit="return confirm('Apakah Anda yakin ingin menghapus catatan potongan ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                            class="p-2 text-slate-400 hover:text-rose-600 hover:bg-rose-50 rounded-xl transition"
                                            title="Hapus">
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
                                        <i class="fa-solid fa-scissors text-2xl"></i>
                                    </div>
                                    <p class="font-bold text-slate-600 text-sm">Belum Ada Catatan Potongan</p>
                                    <p class="text-xs text-slate-400 mt-1">Klik tombol diatas untuk menambahkan data
                                        potongan baru.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- PAGINATION -->
            @if (method_exists($deductions, 'links'))
                <div class="p-4 border-t border-slate-100 bg-slate-50/50">
                    {{ $deductions->links() }}
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
