<x-app-layout>
    <div class="space-y-6">
        <!-- HEADER PAGE -->
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div>
                <h1 class="text-2xl font-bold text-slate-800 tracking-tight">Master Data Departemen</h1>
                <p class="text-xs text-slate-500 mt-1">Kelola daftar divisi dan departemen operasional GB Parking.</p>
            </div>
            <div>
                <a href="{{ route('departments.create') }}"
                    class="px-4 py-2.5 bg-[#FF6B00] hover:bg-[#e66000] text-white font-bold rounded-xl text-xs shadow-lg shadow-orange-500/20 flex items-center space-x-2 transition">
                    <i class="fa-solid fa-plus"></i>
                    <span>Tambah Departemen</span>
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
                    <i class="fa-solid fa-building"></i>
                </div>
                <div>
                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Total Departemen</p>
                    <h4 class="text-base font-extrabold text-slate-800 tracking-tight">
                        {{ number_format($totalDepartments) }} Divisi</h4>
                </div>
            </div>

            <div class="bg-white p-4 rounded-2xl border border-slate-100 shadow-sm flex items-center space-x-3.5">
                <div
                    class="w-11 h-11 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center font-bold text-base shrink-0">
                    <i class="fa-solid fa-briefcase"></i>
                </div>
                <div>
                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Total Jabatan</p>
                    <h4 class="text-base font-extrabold text-slate-800 tracking-tight">
                        {{ number_format($totalPositions) }} Jabatan</h4>
                </div>
            </div>

            <div class="bg-white p-4 rounded-2xl border border-slate-100 shadow-sm flex items-center space-x-3.5">
                <div
                    class="w-11 h-11 rounded-xl bg-purple-50 text-purple-600 flex items-center justify-center font-bold text-base shrink-0">
                    <i class="fa-solid fa-chart-pie"></i>
                </div>
                <div>
                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Rata-rata Jabatan</p>
                    <h4 class="text-base font-extrabold text-slate-800 tracking-tight">{{ $avgPositions }} Posisi/Dept
                    </h4>
                </div>
            </div>
        </div>

        <!-- TABEL DATA DEPARTEMEN -->
        <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
            <div class="p-4 border-b border-slate-100 flex items-center justify-between gap-4 bg-slate-50/50">
                <form method="GET" action="{{ route('departments.index') }}" class="relative w-full sm:w-80">
                    <input type="text" name="search" value="{{ request('search') }}"
                        placeholder="Cari Kode atau Nama Departemen..."
                        class="w-full pl-9 pr-3.5 py-2 rounded-xl border border-slate-200 text-xs focus:outline-none focus:border-[#FF6B00] bg-white">
                    <i class="fa-solid fa-magnifying-glass absolute left-3 top-2.5 text-slate-400 text-xs"></i>
                </form>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs">
                    <thead
                        class="bg-slate-50 text-slate-400 font-bold border-b border-slate-100 uppercase tracking-wider">
                        <tr>
                            <th class="p-4">Kode Divisi</th>
                            <th class="p-4">Nama Departemen</th>
                            <th class="p-4">Jumlah Jabatan</th>
                            <th class="p-4 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 text-slate-700">
                        @forelse ($departments as $dept)
                            <tr class="hover:bg-slate-50/80 transition">
                                <td class="p-4">
                                    <span
                                        class="px-2.5 py-1 bg-slate-100 text-slate-800 font-bold font-mono rounded-lg text-[11px] border border-slate-200">
                                        {{ $dept->code ?? '-' }}
                                    </span>
                                </td>
                                <td class="p-4">
                                    <div class="flex items-center space-x-3">
                                        <div
                                            class="w-8 h-8 rounded-lg bg-orange-50 text-[#FF6B00] font-bold flex items-center justify-center text-xs shrink-0">
                                            <i class="fa-solid fa-building"></i>
                                        </div>
                                        <span class="font-bold text-slate-800 text-xs">{{ $dept->name }}</span>
                                    </div>
                                </td>
                                <td class="p-4 font-semibold text-slate-600">
                                    <span class="px-2.5 py-1 bg-blue-50 text-blue-600 font-bold rounded-lg text-[10px]">
                                        <i class="fa-solid fa-sitemap mr-1"></i>{{ $dept->positions_count ?? 0 }}
                                        Jabatan
                                    </span>
                                </td>
                                <td class="p-4 text-center">
                                    <div class="flex items-center justify-center space-x-1">
                                        <a href="{{ route('departments.edit', $dept->id) }}"
                                            class="w-7 h-7 rounded-lg text-amber-500 hover:bg-amber-50 flex items-center justify-center transition"
                                            title="Edit Departemen">
                                            <i class="fa-solid fa-pen text-xs"></i>
                                        </a>
                                        <form action="{{ route('departments.destroy', $dept->id) }}" method="POST"
                                            class="inline-block"
                                            onsubmit="return confirm('Yakin ingin menghapus departemen {{ $dept->name }}?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                class="w-7 h-7 rounded-lg text-rose-500 hover:bg-rose-50 flex items-center justify-center transition"
                                                title="Hapus Departemen">
                                                <i class="fa-solid fa-trash text-xs"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="p-12 text-center text-slate-400">
                                    <div
                                        class="w-16 h-16 mx-auto bg-slate-50 rounded-full flex items-center justify-center mb-3 text-slate-300">
                                        <i class="fa-solid fa-building text-2xl"></i>
                                    </div>
                                    <p class="font-bold text-slate-600 text-sm">Belum Ada Data Departemen</p>
                                    <p class="text-xs text-slate-400 mt-1">Klik tombol 'Tambah Departemen' di atas untuk
                                        memasukkan data divisi baru.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if (method_exists($departments, 'links'))
                <div class="p-4 border-t border-slate-100 bg-slate-50/50">
                    {{ $departments->links() }}
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
