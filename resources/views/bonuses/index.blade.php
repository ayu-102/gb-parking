<x-app-layout>
    <div class="space-y-6">

        <!-- HEADER PAGE -->
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div>
                <h1 class="text-2xl font-bold text-slate-800 tracking-tight">Bonus & Insentif</h1>
                <p class="text-xs text-slate-500 mt-1">Monitoring dan kelola pendapatan tambahan (Bonus Target, Insentif
                    Layanan, dll) karyawan.</p>
            </div>
            <div>
                <a href="{{ route('bonuses.create') }}"
                    class="px-4 py-2.5 bg-gradient-to-r from-[#FF6B00] to-[#FF4500] hover:from-[#e66000] hover:to-[#e03d00] text-white font-bold rounded-xl text-xs shadow-lg shadow-orange-500/20 flex items-center space-x-2 transition transform active:scale-95">
                    <i class="fa-solid fa-plus text-xs"></i>
                    <span>Tambah Bonus / Insentif</span>
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

        <!-- 4 STATISTIK CARDS (BERDASARKAN PERIODE) -->
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
            <!-- Total Pengeluaran Bonus & Insentif -->
            <div class="bg-white p-4 rounded-2xl border border-slate-100 shadow-sm flex items-center justify-between">
                <div>
                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Total Pendapatan</p>
                    <h3 class="text-lg font-extrabold text-slate-800 mt-1">
                        Rp {{ number_format($totalNominal, 0, ',', '.') }}
                    </h3>
                    <span class="text-[10px] text-slate-400 font-medium">
                        Periode: {{ \Carbon\Carbon::parse($selectedPeriod)->translatedFormat('F Y') }}
                    </span>
                </div>
                <div
                    class="w-10 h-10 bg-emerald-50 rounded-xl flex items-center justify-center text-emerald-500 font-bold">
                    <i class="fa-solid fa-wallet text-sm"></i>
                </div>
            </div>

            <!-- Total Penerima -->
            <div class="bg-white p-4 rounded-2xl border border-slate-100 shadow-sm flex items-center justify-between">
                <div>
                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Total Penerima</p>
                    <h3 class="text-xl font-extrabold text-blue-600 mt-1">
                        {{ $totalPenerima }} <span class="text-xs font-medium text-slate-400">Orang</span>
                    </h3>
                    <span class="text-[10px] text-slate-400">Karyawan Terdata</span>
                </div>
                <div class="w-10 h-10 bg-blue-50 rounded-xl flex items-center justify-center text-blue-500 font-bold">
                    <i class="fa-solid fa-users text-sm"></i>
                </div>
            </div>

            <!-- Total Nominal Bonus -->
            <div class="bg-white p-4 rounded-2xl border border-slate-100 shadow-sm flex items-center justify-between">
                <div>
                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Total Bonus</p>
                    <h3 class="text-lg font-extrabold text-purple-600 mt-1">
                        Rp {{ number_format($totalBonus, 0, ',', '.') }}
                    </h3>
                    <span class="text-[10px] text-purple-600 font-bold">Kategori Bonus</span>
                </div>
                <div
                    class="w-10 h-10 bg-purple-50 rounded-xl flex items-center justify-center text-purple-500 font-bold">
                    <i class="fa-solid fa-gift text-sm"></i>
                </div>
            </div>

            <!-- Total Nominal Insentif -->
            <div class="bg-white p-4 rounded-2xl border border-slate-100 shadow-sm flex items-center justify-between">
                <div>
                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Total Insentif</p>
                    <h3 class="text-lg font-extrabold text-amber-500 mt-1">
                        Rp {{ number_format($totalInsentif, 0, ',', '.') }}
                    </h3>
                    <span class="text-[10px] text-amber-600 font-bold">Kategori Insentif</span>
                </div>
                <div class="w-10 h-10 bg-amber-50 rounded-xl flex items-center justify-center text-amber-500 font-bold">
                    <i class="fa-solid fa-award text-sm"></i>
                </div>
            </div>
        </div>

        <!-- MAIN CONTAINER TABLE -->
        <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">

            <!-- QUICK TAB NAVIGATION & TOOLBAR FILTER -->
            <div class="p-4 border-b border-slate-100 bg-slate-50/50 space-y-3">
                <!-- TAB FILTER CEPAT -->
                <div class="flex items-center space-x-2 border-b border-slate-200 pb-2">
                    <a href="{{ route('bonuses.index', array_merge(request()->query(), ['type' => 'all'])) }}"
                        class="px-3 py-1.5 rounded-lg text-xs font-bold transition {{ request('type', 'all') == 'all' ? 'bg-[#FF6B00] text-white shadow-sm' : 'text-slate-500 hover:text-slate-800 hover:bg-slate-100' }}">
                        Semua Data
                    </a>
                    <a href="{{ route('bonuses.index', array_merge(request()->query(), ['type' => 'bonus'])) }}"
                        class="px-3 py-1.5 rounded-lg text-xs font-bold transition {{ request('type') == 'bonus' ? 'bg-[#FF6B00] text-white shadow-sm' : 'text-slate-500 hover:text-slate-800 hover:bg-slate-100' }}">
                        Bonus
                    </a>
                    <a href="{{ route('bonuses.index', array_merge(request()->query(), ['type' => 'incentive'])) }}"
                        class="px-3 py-1.5 rounded-lg text-xs font-bold transition {{ request('type') == 'incentive' ? 'bg-[#FF6B00] text-white shadow-sm' : 'text-slate-500 hover:text-slate-800 hover:bg-slate-100' }}">
                        Insentif
                    </a>
                </div>

                <!-- FILTER FORM -->
                <form method="GET" action="{{ route('bonuses.index') }}"
                    class="flex flex-col md:flex-row items-center gap-3">
                    <input type="hidden" name="type" value="{{ request('type', 'all') }}">

                    <!-- Search Input -->
                    <div class="relative w-full md:w-64">
                        <input type="text" name="search" value="{{ request('search') }}"
                            placeholder="Cari karyawan, NIK, atau judul..."
                            class="w-full pl-9 pr-3.5 py-2 rounded-xl border border-slate-200 text-xs focus:outline-none focus:border-[#FF6B00] bg-white">
                        <i class="fa-solid fa-magnifying-glass absolute left-3.5 top-2.5 text-slate-400 text-xs"></i>
                    </div>

                    <!-- Filter Periode (Bulan & Tahun) -->
                    <div class="w-full md:w-auto">
                        <input type="month" name="period" value="{{ request('period', date('Y-m')) }}"
                            class="w-full px-3.5 py-2 rounded-xl border border-slate-200 text-xs text-slate-700 focus:outline-none focus:border-[#FF6B00] bg-white font-medium">
                    </div>

                    <button type="submit"
                        class="w-full md:w-auto px-4 py-2 bg-slate-800 hover:bg-slate-900 text-white font-bold rounded-xl text-xs transition">
                        Filter
                    </button>

                    @if (request()->filled('search') || request('type') != 'all' || request('period') != date('Y-m'))
                        <a href="{{ route('bonuses.index') }}"
                            class="text-xs font-bold text-rose-500 hover:underline px-1">Reset Filter</a>
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
                            <th class="p-4">Jenis</th>
                            <th class="p-4">Judul / Deskripsi</th>
                            <th class="p-4">Jumlah (Nominal)</th>
                            <th class="p-4 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 text-slate-700">
                        @forelse ($bonuses as $item)
                            <tr class="hover:bg-slate-50/80 transition">
                                <!-- Tanggal -->
                                <td class="p-4 font-bold text-slate-800 whitespace-nowrap">
                                    {{ \Carbon\Carbon::parse($item->date)->format('d M Y') }}
                                </td>

                                <!-- Karyawan Info -->
                                <td class="p-4 whitespace-nowrap">
                                    <div class="flex items-center space-x-3">
                                        <div
                                            class="w-8 h-8 rounded-full bg-orange-50 border border-orange-200 flex items-center justify-center font-bold text-[#FF6B00] text-xs uppercase">
                                            {{ substr($item->employee->name ?? 'K', 0, 2) }}
                                        </div>
                                        <div>
                                            <p class="font-bold text-slate-800">{{ $item->employee->name ?? '-' }}</p>
                                            <p class="text-[10px] text-slate-400 font-mono">
                                                {{ $item->employee->nik ?? '-' }}</p>
                                        </div>
                                    </div>
                                </td>

                                <!-- Badge Jenis -->
                                <td class="p-4 whitespace-nowrap">
                                    @if ($item->type == 'bonus')
                                        <span
                                            class="px-2.5 py-1 bg-purple-50 text-purple-700 border border-purple-200 font-bold rounded-lg text-[10px] inline-flex items-center gap-1.5">
                                            <i class="fa-solid fa-circle text-[7px] text-purple-500"></i> Bonus
                                        </span>
                                    @else
                                        <span
                                            class="px-2.5 py-1 bg-amber-50 text-amber-700 border border-amber-200 font-bold rounded-lg text-[10px] inline-flex items-center gap-1.5">
                                            <i class="fa-solid fa-circle text-[7px] text-amber-500"></i> Insentif
                                        </span>
                                    @endif
                                </td>

                                <!-- Judul & Deskripsi -->
                                <td class="p-4">
                                    <div class="font-bold text-slate-800">{{ $item->title }}</div>
                                    <div class="text-[11px] text-slate-400 max-w-xs truncate">
                                        {{ $item->description ?? '-' }}</div>
                                </td>

                                <!-- Nominal Rp -->
                                <td class="p-4 font-extrabold text-emerald-600 font-mono text-sm whitespace-nowrap">
                                    Rp {{ number_format($item->amount, 0, ',', '.') }}
                                </td>

                                <!-- Aksi -->
                                <td class="p-4 text-center space-x-1 whitespace-nowrap">
                                    <a href="{{ route('bonuses.edit', $item->id) }}"
                                        class="p-2 text-slate-400 hover:text-amber-600 hover:bg-amber-50 rounded-xl transition inline-block">
                                        <i class="fa-solid fa-pen text-xs"></i>
                                    </a>
                                    <form action="{{ route('bonuses.destroy', $item->id) }}" method="POST"
                                        class="inline-block"
                                        onsubmit="return confirm('Hapus data bonus/insentif ini?')">
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
                                <td colspan="6" class="p-12 text-center text-slate-400">
                                    <div
                                        class="w-16 h-16 mx-auto bg-slate-50 rounded-full flex items-center justify-center mb-3 text-slate-300">
                                        <i class="fa-solid fa-gift text-2xl"></i>
                                    </div>
                                    <p class="font-bold text-slate-600 text-sm">Tidak Ada Data Bonus / Insentif</p>
                                    <p class="text-xs text-slate-400 mt-1">Gunakan filter lain atau klik 'Tambah Bonus
                                        / Insentif' di atas.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- PAGINATION -->
            @if (method_exists($bonuses, 'links'))
                <div class="p-4 border-t border-slate-100 bg-slate-50/50">
                    {{ $bonuses->links() }}
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
