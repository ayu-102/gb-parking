<x-app-layout>
    <div class="space-y-6">

        <!-- HEADER PAGE -->
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div>
                <h1 class="text-2xl font-bold text-slate-800 tracking-tight">Master Komponen Gaji</h1>
                <p class="text-xs text-slate-500 mt-1">Aturan standar tunjangan, pendapatan, dan potongan untuk kalkulasi
                    payroll.</p>
            </div>
            <div>
                <a href="{{ route('salary-components.create') }}"
                    class="px-4 py-2.5 bg-gradient-to-r from-[#FF6B00] to-[#FF4500] hover:from-[#e66000] hover:to-[#e03d00] text-white font-bold rounded-xl text-xs shadow-lg shadow-orange-500/20 flex items-center space-x-2 transition transform active:scale-95">
                    <i class="fa-solid fa-plus text-xs"></i>
                    <span>Tambah Komponen</span>
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
            <!-- Total Komponen -->
            <div class="bg-white p-5 rounded-2xl border border-slate-100 shadow-sm flex items-center justify-between">
                <div>
                    <p class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">Total Komponen</p>
                    <h3 class="text-2xl font-extrabold text-slate-800 mt-1">{{ $totalComponents }} <span
                            class="text-xs font-medium text-slate-400">Jenis</span></h3>
                    <span class="text-[10px] text-slate-400 font-medium">Aktif di sistem</span>
                </div>
                <div
                    class="w-12 h-12 bg-orange-50 rounded-2xl flex items-center justify-center text-[#FF6B00] font-bold text-lg">
                    <i class="fa-solid fa-layer-group"></i>
                </div>
            </div>

            <!-- Tunjangan / Pendapatan -->
            <div class="bg-white p-5 rounded-2xl border border-slate-100 shadow-sm flex items-center justify-between">
                <div>
                    <p class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">Pendapatan (+)</p>
                    <h3 class="text-2xl font-extrabold text-emerald-600 mt-1">{{ $totalAllowances }} <span
                            class="text-xs font-medium text-slate-400">Tunjangan</span></h3>
                    <span class="text-[10px] text-slate-400 font-medium">Penambah gaji</span>
                </div>
                <div
                    class="w-12 h-12 bg-emerald-50 rounded-2xl flex items-center justify-center text-emerald-500 font-bold text-lg">
                    <i class="fa-solid fa-[#fa-arrow-up-right-dots] fa-circle-arrow-up"></i>
                </div>
            </div>

            <!-- Potongan Tetap -->
            <div class="bg-white p-5 rounded-2xl border border-slate-100 shadow-sm flex items-center justify-between">
                <div>
                    <p class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">Potongan (-)</p>
                    <h3 class="text-2xl font-extrabold text-rose-600 mt-1">{{ $totalDeductions }} <span
                            class="text-xs font-medium text-slate-400">Potongan</span></h3>
                    <span class="text-[10px] text-slate-400 font-medium">Pengurang gaji</span>
                </div>
                <div
                    class="w-12 h-12 bg-rose-50 rounded-2xl flex items-center justify-center text-rose-500 font-bold text-lg">
                    <i class="fa-solid fa-circle-arrow-down"></i>
                </div>
            </div>

            <!-- Rumus Persentase -->
            <div class="bg-white p-5 rounded-2xl border border-slate-100 shadow-sm flex items-center justify-between">
                <div>
                    <p class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">Berbasis Persentase</p>
                    <h3 class="text-2xl font-extrabold text-indigo-600 mt-1">{{ $totalPercentage }} <span
                            class="text-xs font-medium text-slate-400">Rumus</span></h3>
                    <span class="text-[10px] text-slate-400 font-medium">BPJS / Pajak / Prosen</span>
                </div>
                <div
                    class="w-12 h-12 bg-indigo-50 rounded-2xl flex items-center justify-center text-indigo-500 font-bold text-lg">
                    <i class="fa-solid fa-percent"></i>
                </div>
            </div>
        </div>

        <!-- TABEL DATA UTAMA -->
        <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">

            <!-- FILTER & SEARCH -->
            <div
                class="p-4 border-b border-slate-100 flex flex-col md:flex-row md:items-center justify-between gap-4 bg-slate-50/50">
                <form method="GET" action="{{ route('salary-components.index') }}"
                    class="flex flex-col sm:flex-row items-center gap-3 w-full md:w-auto">
                    <!-- Input Cari -->
                    <div class="relative w-full sm:w-72">
                        <input type="text" name="search" value="{{ request('search') }}"
                            placeholder="Cari Kode atau Nama Komponen..."
                            class="w-full pl-9 pr-3.5 py-2.5 rounded-xl border border-slate-200 text-xs focus:outline-none focus:border-[#FF6B00] bg-white">
                        <i class="fa-solid fa-magnifying-glass absolute left-3.5 top-3 text-slate-400 text-xs"></i>
                    </div>

                    <!-- Filter Dropdown Tipe -->
                    <select name="type" onchange="this.form.submit()"
                        class="w-full sm:w-48 px-3 py-2.5 rounded-xl border border-slate-200 text-xs focus:outline-none focus:border-[#FF6B00] bg-white font-medium text-slate-700">
                        <option value="Semua">Semua Tipe</option>
                        <option value="allowance" {{ request('type') == 'allowance' ? 'selected' : '' }}>Pendapatan (+)
                        </option>
                        <option value="deduction" {{ request('type') == 'deduction' ? 'selected' : '' }}>Potongan (-)
                        </option>
                    </select>

                    @if (request()->filled('search') || request()->filled('type'))
                        <a href="{{ route('salary-components.index') }}"
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
                            <th class="p-4">Kode</th>
                            <th class="p-4">Nama Komponen</th>
                            <th class="p-4">Tipe Komponen</th>
                            <th class="p-4">Jenis Hitungan</th>
                            <th class="p-4 text-right">Nilai / Nominal Default</th>
                            <th class="p-4 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 text-slate-700">
                        @forelse ($components as $item)
                            <tr class="hover:bg-slate-50/80 transition">
                                <!-- Kode -->
                                <td class="p-4 whitespace-nowrap">
                                    <span
                                        class="px-2.5 py-1 bg-slate-100 text-slate-800 rounded-lg text-[11px] font-mono font-bold tracking-wider border border-slate-200">
                                        {{ $item->code }}
                                    </span>
                                </td>

                                <!-- Nama Komponen -->
                                <td class="p-4 font-bold text-slate-800 whitespace-nowrap">
                                    {{ $item->name }}
                                </td>

                                <!-- Tipe Badge -->
                                <td class="p-4 whitespace-nowrap">
                                    @if ($item->type == 'allowance')
                                        <span
                                            class="px-2.5 py-1 bg-emerald-50 text-emerald-700 border border-emerald-200 font-bold rounded-lg text-[10px] inline-flex items-center gap-1">
                                            <i class="fa-solid fa-arrow-up text-[9px]"></i> Pendapatan (+)
                                        </span>
                                    @else
                                        <span
                                            class="px-2.5 py-1 bg-rose-50 text-rose-700 border border-rose-200 font-bold rounded-lg text-[10px] inline-flex items-center gap-1">
                                            <i class="fa-solid fa-arrow-down text-[9px]"></i> Potongan (-)
                                        </span>
                                    @endif
                                </td>

                                <!-- Jenis Hitungan Badge -->
                                <td class="p-4 whitespace-nowrap">
                                    @if ($item->amount_type == 'fixed')
                                        <span
                                            class="px-2.5 py-1 bg-blue-50 text-blue-700 border border-blue-200 font-bold rounded-lg text-[10px]">
                                            Nominal Tetap (Rp)
                                        </span>
                                    @else
                                        <span
                                            class="px-2.5 py-1 bg-purple-50 text-purple-700 border border-purple-200 font-bold rounded-lg text-[10px]">
                                            Persentase (%)
                                        </span>
                                    @endif
                                </td>

                                <!-- Nominal / Nilai -->
                                <td class="p-4 font-extrabold text-right whitespace-nowrap text-slate-900">
                                    @if ($item->amount_type == 'fixed')
                                        Rp {{ number_format($item->amount, 0, ',', '.') }}
                                    @else
                                        <span class="text-purple-600">{{ number_format($item->amount, 1) }} %</span>
                                    @endif
                                </td>

                                <!-- Tombol Aksi -->
                                <td class="p-4 text-center space-x-1 whitespace-nowrap">
                                    <a href="{{ route('salary-components.edit', $item->id) }}"
                                        class="p-2 text-slate-400 hover:text-amber-600 hover:bg-amber-50 rounded-xl transition inline-block"
                                        title="Edit">
                                        <i class="fa-solid fa-pen text-xs"></i>
                                    </a>
                                    <form action="{{ route('salary-components.destroy', $item->id) }}" method="POST"
                                        class="inline-block"
                                        onsubmit="return confirm('Apakah Anda yakin ingin menghapus komponen gaji ini?')">
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
                                <td colspan="6" class="p-12 text-center text-slate-400">
                                    <div
                                        class="w-16 h-16 mx-auto bg-slate-50 rounded-full flex items-center justify-center mb-3 text-slate-300">
                                        <i class="fa-solid fa-wallet text-2xl"></i>
                                    </div>
                                    <p class="font-bold text-slate-600 text-sm">Belum Ada Komponen Gaji</p>
                                    <p class="text-xs text-slate-400 mt-1">Klik 'Tambah Komponen' di atas untuk
                                        menambah aturan tunjangan / potongan baru.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- PAGINATION -->
            @if (method_exists($components, 'links'))
                <div class="p-4 border-t border-slate-100 bg-slate-50/50">
                    {{ $components->links() }}
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
