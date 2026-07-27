<x-app-layout>
    <div class="space-y-6">

        <!-- HEADER PAGE -->
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div>
                <h1 class="text-2xl font-bold text-slate-800 tracking-tight">Jadwal Shift Karyawan</h1>
                <p class="text-xs text-slate-500 mt-1">Monitoring dan plotting penugasan shift harian karyawan GB
                    Parking.</p>
            </div>
            <div class="flex items-center gap-2">
                <!-- TOMBOL ATUR JAM SHIFT (Ke Halaman Master Shift) -->
                <a href="{{ route('shift-templates.index') }}"
                    class="px-4 py-2.5 bg-slate-800 hover:bg-slate-900 text-white font-bold rounded-xl text-xs shadow-md transition flex items-center space-x-2">
                    <i class="fa-solid fa-clock text-xs"></i>
                    <span>Atur Jam Shift</span>
                </a>

                <!-- TOMBOL BUAT JADWAL SHIFT -->
                <a href="{{ route('employee-shifts.create') }}"
                    class="px-4 py-2.5 bg-gradient-to-r from-[#FF6B00] to-[#FF4500] hover:from-[#e66000] hover:to-[#e03d00] text-white font-bold rounded-xl text-xs shadow-lg shadow-orange-500/20 flex items-center space-x-2 transition transform active:scale-95">
                    <i class="fa-solid fa-plus text-xs"></i>
                    <span>Buat Jadwal Shift</span>
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

        <!-- 5 STATISTIK CARDS (DINAMIS MENGIKUTI TANGGAL FILTER) -->
        <div class="grid grid-cols-2 lg:grid-cols-5 gap-3">
            <!-- Total Shift Master -->
            <div
                class="bg-white p-4 rounded-2xl border border-slate-100 shadow-sm flex items-center justify-between col-span-2 sm:col-span-1">
                <div>
                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Shift Aktif</p>
                    <h3 class="text-xl font-extrabold text-purple-600 mt-1">{{ $totalShiftAktif }} <span
                            class="text-xs font-medium text-slate-400">Master</span></h3>
                    <span class="text-[10px] text-slate-400">Template Sistem</span>
                </div>
                <div
                    class="w-10 h-10 bg-purple-50 rounded-xl flex items-center justify-center text-purple-500 font-bold">
                    <i class="fa-solid fa-business-time text-sm"></i>
                </div>
            </div>

            <!-- Total Karyawan Terjadwal -->
            <div class="bg-white p-4 rounded-2xl border border-slate-100 shadow-sm flex items-center justify-between">
                <div>
                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Terjadwal</p>
                    <h3 class="text-xl font-extrabold text-blue-600 mt-1">{{ $totalTerjadwal }} <span
                            class="text-xs font-medium text-slate-400">Orang</span></h3>
                    <span class="text-[10px] text-slate-400 font-medium">Tgl:
                        {{ \Carbon\Carbon::parse($filterDate)->format('d M Y') }}</span>
                </div>
                <div class="w-10 h-10 bg-blue-50 rounded-xl flex items-center justify-center text-blue-500 font-bold">
                    <i class="fa-solid fa-user-group text-sm"></i>
                </div>
            </div>

            <!-- Shift Pagi -->
            <div class="bg-white p-4 rounded-2xl border border-slate-100 shadow-sm flex items-center justify-between">
                <div>
                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Shift Pagi</p>
                    <h3 class="text-xl font-extrabold text-emerald-600 mt-1">{{ $shiftPagi }} <span
                            class="text-xs font-medium text-slate-400">Orang</span></h3>
                    <span class="text-[10px] text-emerald-600 font-bold">
                        {{ $templatePagi ? substr($templatePagi->start_time, 0, 5) . ' - ' . substr($templatePagi->end_time, 0, 5) : '07:00 - 15:00' }}
                    </span>
                </div>
                <div
                    class="w-10 h-10 bg-emerald-50 rounded-xl flex items-center justify-center text-emerald-500 font-bold">
                    <i class="fa-solid fa-sun text-sm"></i>
                </div>
            </div>

            <!-- Shift Siang -->
            <div class="bg-white p-4 rounded-2xl border border-slate-100 shadow-sm flex items-center justify-between">
                <div>
                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Shift Siang</p>
                    <h3 class="text-xl font-extrabold text-amber-500 mt-1">{{ $shiftSiang }} <span
                            class="text-xs font-medium text-slate-400">Orang</span></h3>
                    <span class="text-[10px] text-amber-600 font-bold">
                        {{ $templateSiang ? substr($templateSiang->start_time, 0, 5) . ' - ' . substr($templateSiang->end_time, 0, 5) : '15:00 - 23:00' }}
                    </span>
                </div>
                <div class="w-10 h-10 bg-amber-50 rounded-xl flex items-center justify-center text-amber-500 font-bold">
                    <i class="fa-solid fa-cloud-sun text-sm"></i>
                </div>
            </div>

            <!-- Shift Malam -->
            <div class="bg-white p-4 rounded-2xl border border-slate-100 shadow-sm flex items-center justify-between">
                <div>
                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Shift Malam</p>
                    <h3 class="text-xl font-extrabold text-indigo-600 mt-1">{{ $shiftMalam }} <span
                            class="text-xs font-medium text-slate-400">Orang</span></h3>
                    <span class="text-[10px] text-indigo-600 font-bold">
                        {{ $templateMalam ? substr($templateMalam->start_time, 0, 5) . ' - ' . substr($templateMalam->end_time, 0, 5) : '23:00 - 07:00' }}
                    </span>
                </div>
                <div
                    class="w-10 h-10 bg-indigo-50 rounded-xl flex items-center justify-center text-indigo-500 font-bold">
                    <i class="fa-solid fa-moon text-sm"></i>
                </div>
            </div>
        </div>

        <!-- TABEL JADWAL SHIFT -->
        <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">

            <!-- FILTER TOOLBAR -->
            <div class="p-4 border-b border-slate-100 bg-slate-50/50">
                <form method="GET" action="{{ route('employee-shifts.index') }}"
                    class="flex flex-col md:flex-row items-center gap-3">

                    <!-- Search Input -->
                    <div class="relative w-full md:w-64">
                        <input type="text" name="search" value="{{ request('search') }}"
                            placeholder="Cari Karyawan / NIK..."
                            class="w-full pl-9 pr-3.5 py-2 rounded-xl border border-slate-200 text-xs focus:outline-none focus:border-[#FF6B00] bg-white">
                        <i class="fa-solid fa-magnifying-glass absolute left-3.5 top-2.5 text-slate-400 text-xs"></i>
                    </div>

                    <!-- Filter Tanggal -->
                    <div class="w-full md:w-auto">
                        <input type="date" name="date" value="{{ request('date', date('Y-m-d')) }}"
                            class="w-full px-3.5 py-2 rounded-xl border border-slate-200 text-xs text-slate-700 focus:outline-none focus:border-[#FF6B00] bg-white font-medium">
                    </div>

                    <!-- Filter Template Shift -->
                    <div class="w-full md:w-48">
                        <select name="shift_id" onchange="this.form.submit()"
                            class="w-full px-3 py-2 rounded-xl border border-slate-200 text-xs focus:outline-none focus:border-[#FF6B00] bg-white font-medium text-slate-700">
                            <option value="Semua">Semua Shift</option>
                            @foreach ($shiftTemplates as $shift)
                                <option value="{{ $shift->id }}"
                                    {{ request('shift_id') == $shift->id ? 'selected' : '' }}>
                                    {{ $shift->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <button type="submit"
                        class="w-full md:w-auto px-4 py-2 bg-slate-800 hover:bg-slate-900 text-white font-bold rounded-xl text-xs transition">
                        Filter
                    </button>

                    @if (request()->filled('search') || request()->filled('shift_id') || request('date') != date('Y-m-d'))
                        <a href="{{ route('employee-shifts.index') }}"
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
                            <th class="p-4">Shift Kerja</th>
                            <th class="p-4 text-center">Jam Kerja</th>
                            <th class="p-4">Catatan Penugasan</th>
                            <th class="p-4 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 text-slate-700">
                        @forelse ($schedules as $item)
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

                                <!-- Shift Badge Warna -->
                                <td class="p-4 whitespace-nowrap">
                                    @php
                                        $shiftName = strtolower($item->shiftTemplate->name ?? '');
                                    @endphp

                                    @if (str_contains($shiftName, 'pagi'))
                                        <span
                                            class="px-2.5 py-1 bg-emerald-50 text-emerald-700 border border-emerald-200 font-bold rounded-lg text-[10px] inline-flex items-center gap-1.5">
                                            <i class="fa-solid fa-circle text-[7px] text-emerald-500"></i>
                                            {{ $item->shiftTemplate->name }}
                                        </span>
                                    @elseif(str_contains($shiftName, 'siang'))
                                        <span
                                            class="px-2.5 py-1 bg-amber-50 text-amber-700 border border-amber-200 font-bold rounded-lg text-[10px] inline-flex items-center gap-1.5">
                                            <i class="fa-solid fa-circle text-[7px] text-amber-500"></i>
                                            {{ $item->shiftTemplate->name }}
                                        </span>
                                    @elseif(str_contains($shiftName, 'malam'))
                                        <span
                                            class="px-2.5 py-1 bg-indigo-50 text-indigo-700 border border-indigo-200 font-bold rounded-lg text-[10px] inline-flex items-center gap-1.5">
                                            <i class="fa-solid fa-circle text-[7px] text-indigo-500"></i>
                                            {{ $item->shiftTemplate->name }}
                                        </span>
                                    @else
                                        <span
                                            class="px-2.5 py-1 bg-slate-100 text-slate-700 border border-slate-200 font-bold rounded-lg text-[10px] inline-flex items-center gap-1.5">
                                            <i class="fa-solid fa-circle text-[7px] text-slate-400"></i>
                                            {{ $item->shiftTemplate->name ?? '-' }}
                                        </span>
                                    @endif
                                </td>

                                <!-- Jam Kerja -->
                                <td class="p-4 text-center font-mono font-bold whitespace-nowrap">
                                    @if ($item->shiftTemplate)
                                        <span
                                            class="px-2.5 py-1 bg-slate-100 rounded-lg text-slate-800 border border-slate-200">
                                            {{ substr($item->shiftTemplate->start_time, 0, 5) }} -
                                            {{ substr($item->shiftTemplate->end_time, 0, 5) }}
                                        </span>
                                    @else
                                        <span class="text-slate-300">-</span>
                                    @endif
                                </td>

                                <!-- Catatan / Pos Penugasan -->
                                <td class="p-4 text-slate-500 max-w-xs truncate">
                                    {{ $item->notes ?? '-' }}
                                </td>

                                <!-- Aksi -->
                                <td class="p-4 text-center space-x-1 whitespace-nowrap">
                                    <a href="{{ route('employee-shifts.edit', $item->id) }}"
                                        class="p-2 text-slate-400 hover:text-amber-600 hover:bg-amber-50 rounded-xl transition inline-block">
                                        <i class="fa-solid fa-pen text-xs"></i>
                                    </a>
                                    <form action="{{ route('employee-shifts.destroy', $item->id) }}" method="POST"
                                        class="inline-block" onsubmit="return confirm('Hapus jadwal shift ini?')">
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
                                        <i class="fa-solid fa-calendar-xmark text-2xl"></i>
                                    </div>
                                    <p class="font-bold text-slate-600 text-sm">Tidak Ada Jadwal Shift</p>
                                    <p class="text-xs text-slate-400 mt-1">Gunakan filter lain atau klik 'Buat Jadwal
                                        Shift' di atas.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if (method_exists($schedules, 'links'))
                <div class="p-4 border-t border-slate-100 bg-slate-50/50">
                    {{ $schedules->links() }}
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
