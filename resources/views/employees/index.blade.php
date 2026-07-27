<x-app-layout>
    <div class="space-y-6">

        <!-- HEADER & BREADCRUMB -->
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div>
                <h1 class="text-2xl font-extrabold text-slate-800 tracking-tight">Master Karyawan</h1>
                <nav class="flex text-xs text-slate-400 space-x-2 mt-1 font-medium">
                    <a href="#" class="hover:text-slate-600">Dashboard</a>
                    <span>&rsaquo;</span>
                    <a href="#" class="hover:text-slate-600">Master Data</a>
                    <span>&rsaquo;</span>
                    <span class="text-slate-600 font-bold">Karyawan</span>
                </nav>
            </div>
            <div class="flex items-center space-x-3">
                <a href="{{ route('employees.create') }}"
                    class="px-4 py-2.5 bg-[#FF6B00] hover:bg-[#e66000] text-white font-bold rounded-xl text-xs shadow-lg shadow-orange-500/20 flex items-center space-x-2 transition">
                    <i class="fa-solid fa-plus"></i>
                    <span>Tambah Karyawan</span>
                </a>
            </div>
        </div>

        <!-- STATISTIC CARDS (Ringkasan Data Karyawan) -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
            <!-- Card 1: Total Karyawan -->
            <div class="bg-white rounded-2xl p-5 border border-slate-100 shadow-sm space-y-3">
                <div class="w-10 h-10 rounded-xl bg-orange-50 text-[#FF6B00] flex items-center justify-center text-lg">
                    <i class="fa-solid fa-users"></i>
                </div>
                <div>
                    <p class="text-xs font-bold text-slate-400 uppercase tracking-wider">Total Karyawan</p>
                    <h3 class="text-2xl font-black text-slate-800 mt-1">
                        {{ method_exists($employees, 'total') ? $employees->total() : $employees->count() }}
                        <span class="text-xs font-normal text-slate-400">Orang</span>
                    </h3>
                </div>
                <div class="pt-2 border-t border-slate-50 flex items-center justify-between text-[11px] font-semibold">
                    <span class="text-emerald-600">
                        <span class="inline-block w-2 h-2 bg-emerald-500 rounded-full mr-1"></span> Aktif
                    </span>
                    <span class="text-rose-500">
                        <span class="inline-block w-2 h-2 bg-rose-500 rounded-full mr-1"></span> Non Aktif
                    </span>
                </div>
            </div>

            <!-- Card 2: Karyawan Tetap -->
            <div class="bg-white rounded-2xl p-5 border border-slate-100 shadow-sm space-y-3">
                <div class="w-10 h-10 rounded-xl bg-orange-50 text-[#FF6B00] flex items-center justify-center text-lg">
                    <i class="fa-solid fa-user-tie"></i> <!-- ICON SUDAH DIPERBAIKI -->
                </div>
                <div>
                    <p class="text-xs font-bold text-slate-400 uppercase tracking-wider">Karyawan Tetap</p>
                    <h3 class="text-2xl font-black text-slate-800 mt-1">
                        {{ $totalTetap ?? 0 }} <span class="text-xs font-normal text-slate-400">Orang</span>
                    </h3>
                </div>
                <p class="text-[11px] font-medium text-slate-400 pt-2 border-t border-slate-50">Status Tetap</p>
            </div>

            <!-- Card 3: Karyawan Kontrak -->
            <div class="bg-white rounded-2xl p-5 border border-slate-100 shadow-sm space-y-3">
                <div class="w-10 h-10 rounded-xl bg-orange-50 text-[#FF6B00] flex items-center justify-center text-lg">
                    <i class="fa-solid fa-file-contract"></i>
                </div>
                <div>
                    <p class="text-xs font-bold text-slate-400 uppercase tracking-wider">Karyawan Kontrak</p>
                    <h3 class="text-2xl font-black text-slate-800 mt-1">
                        {{ $totalKontrak ?? 0 }} <span class="text-xs font-normal text-slate-400">Orang</span>
                    </h3>
                </div>
                <p class="text-[11px] font-medium text-slate-400 pt-2 border-t border-slate-50">Status Kontrak</p>
            </div>

            <!-- Card 4: Karyawan Baru -->
            <div class="bg-white rounded-2xl p-5 border border-slate-100 shadow-sm space-y-3">
                <div class="w-10 h-10 rounded-xl bg-orange-50 text-[#FF6B00] flex items-center justify-center text-lg">
                    <i class="fa-solid fa-user-plus"></i>
                </div>
                <div>
                    <p class="text-xs font-bold text-slate-400 uppercase tracking-wider">Karyawan Baru</p>
                    <h3 class="text-2xl font-black text-slate-800 mt-1">
                        {{ $totalBaru ?? 0 }} <span class="text-xs font-normal text-slate-400">Orang</span>
                    </h3>
                </div>
                <p class="text-[11px] font-medium text-slate-400 pt-2 border-t border-slate-50">Bulan Ini</p>
            </div>
        </div>

        <!-- SEARCH & FILTER BAR -->
        <div class="bg-white rounded-2xl p-4 border border-slate-100 shadow-sm">
            <form method="GET" action="{{ route('employees.index') }}"
                class="grid grid-cols-1 md:grid-cols-12 gap-3 items-center">
                <!-- Search Input -->
                <div class="md:col-span-5 relative">
                    <input type="text" name="search" value="{{ request('search') }}"
                        placeholder="Cari karyawan (NIK, Nama...)..."
                        class="w-full pl-9 pr-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs focus:outline-none focus:border-[#FF6B00]">
                    <i class="fa-solid fa-magnifying-glass absolute left-3.5 top-3 text-slate-400 text-xs"></i>
                </div>

                <!-- Status Filter -->
                <div class="md:col-span-3">
                    <select name="status"
                        class="w-full px-3 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs focus:outline-none focus:border-[#FF6B00] text-slate-600 font-medium">
                        <option value="">Semua Status</option>
                        <option value="Aktif" {{ request('status') == 'Aktif' ? 'selected' : '' }}>Aktif</option>
                        <option value="Nonaktif" {{ request('status') == 'Nonaktif' ? 'selected' : '' }}>Nonaktif
                        </option>
                    </select>
                </div>

                <!-- Tombol Aksi Filter -->
                <div class="md:col-span-4 flex items-center space-x-2">
                    <button type="submit"
                        class="w-full py-2.5 bg-white border border-slate-200 hover:bg-slate-50 font-bold text-slate-700 rounded-xl text-xs flex items-center justify-center space-x-1.5 transition">
                        <i class="fa-solid fa-filter text-slate-400"></i>
                        <span>Filter</span>
                    </button>
                    <a href="{{ route('employees.index') }}"
                        class="p-2.5 text-slate-400 hover:text-slate-600 text-xs font-semibold" title="Reset Filter">
                        <i class="fa-solid fa-rotate-right"></i>
                    </a>
                </div>
            </form>
        </div>

        <!-- NOTIFIKASI SUKSES -->
        @if (session('success'))
            <div
                class="p-4 bg-emerald-50 border border-emerald-200 text-emerald-700 text-xs font-semibold rounded-xl flex items-center space-x-2">
                <i class="fa-solid fa-circle-check text-base"></i>
                <span>{{ session('success') }}</span>
            </div>
        @endif

        <!-- TABEL DATA KARYAWAN -->
        <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs">
                    <thead
                        class="bg-slate-50/80 text-slate-400 font-bold border-b border-slate-100 uppercase tracking-wider">
                        <tr>
                            <th class="p-4 w-12 text-center">No</th>
                            <th class="p-4 w-12 text-center">Foto</th>
                            <th class="p-4">NIK</th>
                            <th class="p-4">Nama Karyawan</th>
                            <th class="p-4">Jabatan</th>
                            <th class="p-4">Lokasi Penempatan</th>
                            <th class="p-4">Gaji Pokok</th>
                            <th class="p-4 text-center">Status</th>
                            <th class="p-4 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 text-slate-700">
                        @forelse ($employees as $employee)
                            <tr class="hover:bg-slate-50/60 transition">
                                <td class="p-4 text-center font-medium text-slate-400">
                                    {{ $loop->iteration }}
                                </td>
                                <td class="p-4 text-center">
                                    <div
                                        class="w-8 h-8 rounded-full bg-orange-100 text-[#FF6B00] font-bold flex items-center justify-center text-xs mx-auto overflow-hidden border border-orange-200">
                                        {{ strtoupper(substr($employee->name, 0, 2)) }}
                                    </div>
                                </td>
                                <td class="p-4 font-bold text-slate-700">{{ $employee->nik }}</td>
                                <td class="p-4 font-bold text-slate-800">
                                    <div>{{ $employee->name }}</div>
                                    <div class="text-[10px] font-normal text-slate-400">{{ $employee->email ?? '-' }}
                                    </div>
                                </td>
                                <td class="p-4 text-slate-600 font-medium">
                                    {{ $employee->position->name ?? ($employee->position->title ?? 'Belum Diatur') }}
                                </td>
                                <td class="p-4 text-slate-600 font-medium">
                                    {{ $employee->location->name ?? 'Belum Diatur' }}
                                </td>
                                <td class="p-4 font-bold text-slate-800">
                                    Rp {{ number_format($employee->basic_salary, 0, ',', '.') }}
                                </td>
                                <td class="p-4 text-center">
                                    <span
                                        class="px-2.5 py-1 {{ $employee->status == 'Aktif' ? 'bg-emerald-100/70 text-emerald-700' : 'bg-rose-100/70 text-rose-700' }} font-bold rounded-lg text-[10px]">
                                        {{ $employee->status }}
                                    </span>
                                </td>
                                <td class="p-4 text-center space-x-1">
                                    <a href="{{ route('employees.edit', $employee->id) }}"
                                        class="p-2 text-amber-500 hover:bg-amber-50 rounded-lg inline-block">
                                        <i class="fa-solid fa-pen"></i>
                                    </a>
                                    <form action="{{ route('employees.destroy', $employee->id) }}" method="POST"
                                        class="inline-block"
                                        onsubmit="return confirm('Yakin ingin menghapus karyawan ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="p-2 text-red-500 hover:bg-red-50 rounded-lg">
                                            <i class="fa-solid fa-trash"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="p-12 text-center text-slate-400">
                                    <i class="fa-solid fa-users-slash text-4xl mb-3 block text-slate-300"></i>
                                    <p class="font-semibold text-sm text-slate-600">Belum ada data karyawan</p>
                                    <p class="text-xs mt-1">Klik tombol 'Tambah Karyawan' di atas untuk memasukkan data
                                        baru.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- PAGINASI -->
            @if (method_exists($employees, 'links'))
                <div class="p-4 border-t border-slate-100">
                    {{ $employees->links() }}
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
