<x-app-layout>
    <div class="space-y-6">

        <!-- HEADER PAGE & TOMBOL TAMBAH -->
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div>
                <h1 class="text-2xl font-bold text-slate-800 tracking-tight">Pengajuan Cuti & Perizinan</h1>
                <p class="text-xs text-slate-500 mt-1">Kelola dan setujui permohonan izin, sakit, atau cuti karyawan GB
                    Parking.</p>
            </div>
            <div>
                <button onclick="openModalCreate()"
                    class="px-4 py-2.5 bg-gradient-to-r from-[#FF6B00] to-[#FF4500] hover:from-[#e66000] hover:to-[#e03d00] text-white font-bold rounded-xl text-xs shadow-lg shadow-orange-500/20 flex items-center space-x-2 transition transform active:scale-95">
                    <i class="fa-solid fa-plus text-xs"></i>
                    <span>Pengajuan Cuti/Izin</span>
                </button>
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
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-3">
            <!-- Total Pengajuan -->
            <div class="bg-white p-4 rounded-2xl border border-slate-100 shadow-sm flex items-center justify-between">
                <div>
                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Total Pengajuan</p>
                    <h3 class="text-xl font-extrabold text-slate-800 mt-1">{{ $totalLeaves ?? 0 }} <span
                            class="text-xs font-medium text-slate-400">Pengajuan</span></h3>
                    <span class="text-[10px] text-slate-400">Keseluruhan</span>
                </div>
                <div
                    class="w-10 h-10 bg-slate-100 rounded-xl flex items-center justify-center text-slate-600 font-bold">
                    <i class="fa-solid fa-folder-open text-sm"></i>
                </div>
            </div>

            <!-- Menunggu Persetujuan -->
            <div class="bg-white p-4 rounded-2xl border border-slate-100 shadow-sm flex items-center justify-between">
                <div>
                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Pending</p>
                    <h3 class="text-xl font-extrabold text-amber-500 mt-1">{{ $pendingLeaves ?? 0 }} <span
                            class="text-xs font-medium text-slate-400">Pengajuan</span></h3>
                    <span class="text-[10px] text-amber-600 font-bold">Butuh Persetujuan</span>
                </div>
                <div class="w-10 h-10 bg-amber-50 rounded-xl flex items-center justify-center text-amber-500 font-bold">
                    <i class="fa-solid fa-clock text-sm"></i>
                </div>
            </div>

            <!-- Disetujui -->
            <div class="bg-white p-4 rounded-2xl border border-slate-100 shadow-sm flex items-center justify-between">
                <div>
                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Disetujui</p>
                    <h3 class="text-xl font-extrabold text-emerald-600 mt-1">{{ $approvedLeaves ?? 0 }} <span
                            class="text-xs font-medium text-slate-400">Izin/Cuti</span></h3>
                    <span class="text-[10px] text-emerald-600 font-bold">Telah Disetujui</span>
                </div>
                <div
                    class="w-10 h-10 bg-emerald-50 rounded-xl flex items-center justify-center text-emerald-500 font-bold">
                    <i class="fa-solid fa-circle-check text-sm"></i>
                </div>
            </div>

            <!-- Ditolak -->
            <div class="bg-white p-4 rounded-2xl border border-slate-100 shadow-sm flex items-center justify-between">
                <div>
                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Ditolak</p>
                    <h3 class="text-xl font-extrabold text-rose-500 mt-1">{{ $rejectedLeaves ?? 0 }} <span
                            class="text-xs font-medium text-slate-400">Pengajuan</span></h3>
                    <span class="text-[10px] text-rose-500 font-bold">Tidak Disetujui</span>
                </div>
                <div class="w-10 h-10 bg-rose-50 rounded-xl flex items-center justify-center text-rose-500 font-bold">
                    <i class="fa-solid fa-circle-xmark text-sm"></i>
                </div>
            </div>
        </div>

        <!-- TABEL DATA PENGAJUAN -->
        <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">

            <!-- FILTER TOOLBAR -->
            <div class="p-4 border-b border-slate-100 bg-slate-50/50">
                <form method="GET" action="{{ route('leaves.index') }}"
                    class="flex flex-col md:flex-row items-center gap-3">

                    <!-- Search Input -->
                    <div class="relative w-full md:w-64">
                        <input type="text" name="search" value="{{ request('search') }}"
                            placeholder="Cari Karyawan / NIK..."
                            class="w-full pl-9 pr-3.5 py-2 rounded-xl border border-slate-200 text-xs focus:outline-none focus:border-[#FF6B00] bg-white">
                        <i class="fa-solid fa-magnifying-glass absolute left-3.5 top-2.5 text-slate-400 text-xs"></i>
                    </div>

                    <!-- Filter Status -->
                    <div class="w-full md:w-44">
                        <select name="status" onchange="this.form.submit()"
                            class="w-full px-3 py-2 rounded-xl border border-slate-200 text-xs focus:outline-none focus:border-[#FF6B00] bg-white font-medium text-slate-700">
                            <option value="">Semua Status</option>
                            <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending
                            </option>
                            <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Disetujui
                            </option>
                            <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Ditolak
                            </option>
                        </select>
                    </div>

                    <!-- Filter Tipe -->
                    <div class="w-full md:w-44">
                        <select name="type" onchange="this.form.submit()"
                            class="w-full px-3 py-2 rounded-xl border border-slate-200 text-xs focus:outline-none focus:border-[#FF6B00] bg-white font-medium text-slate-700">
                            <option value="">Semua Tipe</option>
                            <option value="Cuti" {{ request('type') == 'Cuti' ? 'selected' : '' }}>Cuti Tahunan
                            </option>
                            <option value="Sakit" {{ request('type') == 'Sakit' ? 'selected' : '' }}>Sakit</option>
                            <option value="Izin" {{ request('type') == 'Izin' ? 'selected' : '' }}>Izin Khusus
                            </option>
                        </select>
                    </div>

                    <button type="submit"
                        class="w-full md:w-auto px-4 py-2 bg-slate-800 hover:bg-slate-900 text-white font-bold rounded-xl text-xs transition">
                        Filter
                    </button>

                    @if (request()->filled('search') || request()->filled('status') || request()->filled('type'))
                        <a href="{{ route('leaves.index') }}"
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
                            <th class="p-4">Karyawan</th>
                            <th class="p-4">Tipe</th>
                            <th class="p-4">Tanggal & Durasi</th>
                            <th class="p-4">Alasan</th>
                            <th class="p-4 text-center">Lampiran</th>
                            <th class="p-4 text-center">Status</th>
                            <th class="p-4 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 text-slate-700">
                        @forelse ($leaves ?? [] as $item)
                            <tr class="hover:bg-slate-50/80 transition">
                                <!-- Karyawan -->
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

                                <!-- Tipe -->
                                <td class="p-4 whitespace-nowrap">
                                    <span
                                        class="px-2.5 py-1 bg-slate-100 text-slate-700 font-bold rounded-lg text-[10px]">
                                        {{ $item->type ?? 'Izin' }}
                                    </span>
                                </td>

                                <!-- Tanggal & Durasi -->
                                <td class="p-4 whitespace-nowrap">
                                    <p class="font-bold text-slate-800">
                                        {{ \Carbon\Carbon::parse($item->start_date)->format('d M Y') }}
                                        @if ($item->end_date && $item->end_date != $item->start_date)
                                            - {{ \Carbon\Carbon::parse($item->end_date)->format('d M Y') }}
                                        @endif
                                    </p>
                                    <p class="text-[10px] text-slate-400 font-medium mt-0.5">
                                        {{ $item->duration_days ?? 1 }} Hari Kerja</p>
                                </td>

                                <!-- Alasan -->
                                <td class="p-4 max-w-xs truncate text-slate-600">
                                    {{ $item->reason ?? '-' }}
                                </td>

                                <!-- Lampiran -->
                                <td class="p-4 text-center whitespace-nowrap">
                                    @if (!empty($item->attachment))
                                        <a href="{{ asset('storage/' . $item->attachment) }}" target="_blank"
                                            class="inline-flex items-center gap-1 px-2.5 py-1 bg-blue-50 text-blue-600 border border-blue-200 rounded-lg text-[10px] font-bold hover:bg-blue-100 transition">
                                            <i class="fa-solid fa-paperclip"></i> Lihat File
                                        </a>
                                    @else
                                        <span class="text-slate-300 text-[10px]">-</span>
                                    @endif
                                </td>

                                <!-- Status Badge -->
                                <td class="p-4 text-center whitespace-nowrap">
                                    @if (($item->status ?? 'pending') == 'pending')
                                        <span
                                            class="px-2.5 py-1 bg-amber-50 text-amber-700 border border-amber-200 font-bold rounded-lg text-[10px] inline-flex items-center gap-1.5">
                                            <i class="fa-solid fa-circle text-[7px] text-amber-500"></i> Menunggu
                                        </span>
                                    @elseif(($item->status ?? '') == 'approved')
                                        <span
                                            class="px-2.5 py-1 bg-emerald-50 text-emerald-700 border border-emerald-200 font-bold rounded-lg text-[10px] inline-flex items-center gap-1.5">
                                            <i class="fa-solid fa-circle text-[7px] text-emerald-500"></i> Disetujui
                                        </span>
                                    @else
                                        <span
                                            class="px-2.5 py-1 bg-rose-50 text-rose-700 border border-rose-200 font-bold rounded-lg text-[10px] inline-flex items-center gap-1.5">
                                            <i class="fa-solid fa-circle text-[7px] text-rose-500"></i> Ditolak
                                        </span>
                                    @endif
                                </td>

                                <!-- Aksi -->
                                <td class="p-4 text-center space-x-1 whitespace-nowrap">
                                    @if (($item->status ?? 'pending') == 'pending')
                                        <!-- Tombol Setujui -->
                                        <form action="{{ route('leaves.approve', $item->id) }}" method="POST"
                                            class="inline-block">
                                            @csrf
                                            {{-- Hapus @method('PATCH') di sini --}}
                                            <button type="submit" title="Setujui"
                                                class="p-2 text-emerald-600 hover:bg-emerald-50 rounded-xl transition">
                                                <i class="fa-solid fa-check text-xs"></i>
                                            </button>
                                        </form>

                                        <!-- Tombol Tolak -->
                                        <form action="{{ route('leaves.reject', $item->id) }}" method="POST"
                                            class="inline-block">
                                            @csrf
                                            {{-- Hapus @method('PATCH') di sini --}}
                                            <button type="submit" title="Tolak"
                                                class="p-2 text-rose-600 hover:bg-rose-50 rounded-xl transition">
                                                <i class="fa-solid fa-xmark text-xs"></i>
                                            </button>
                                        </form>
                                    @endif

                                    <!-- Tombol Hapus -->
                                    <form action="{{ route('leaves.destroy', $item->id) }}" method="POST"
                                        class="inline-block" onsubmit="return confirm('Hapus permohonan ini?')">
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
                                        <i class="fa-solid fa-inbox text-2xl"></i>
                                    </div>
                                    <p class="font-bold text-slate-600 text-sm">Belum Ada Pengajuan Masuk</p>
                                    <p class="text-xs text-slate-400 mt-1">Data permohonan izin/cuti karyawan akan
                                        muncul di sini.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if (isset($leaves) && method_exists($leaves, 'links'))
                <div class="p-4 border-t border-slate-100 bg-slate-50/50">
                    {{ $leaves->links() }}
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
