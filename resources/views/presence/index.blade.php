@extends('layouts.karyawan')

@section('content')
    <div class="space-y-6">

        @if (isset($message) && !$employee)
            <div class="bg-rose-50 border border-rose-200 text-rose-700 px-4 py-3 rounded-2xl text-xs font-bold">
                <i class="fa-solid fa-triangle-exclamation mr-1"></i> {{ $message }}
            </div>
        @else
            <!-- 1. WELCOME BANNER & DETAIL SHIFT AKTIF -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-4">
                <!-- BANNER MENYAPA KARYAWAN (2 KOLOM) -->
                <div
                    class="lg:col-span-2 bg-gradient-to-r from-[#FF6B00] to-orange-400 rounded-3xl p-6 text-white shadow-xl shadow-orange-500/10 flex items-center justify-between relative overflow-hidden">
                    <div class="space-y-2 relative z-10">
                        <span
                            class="bg-white/20 backdrop-blur-md text-white font-bold text-[10px] px-3 py-1 rounded-full inline-flex items-center gap-1.5 uppercase tracking-wider">
                            <i class="fa-solid fa-user-check text-amber-200"></i> Sesi Karyawan Aktif
                        </span>
                        <h1 class="text-2xl font-black tracking-tight">
                            Selamat Datang, {{ $employee->name ?? Auth::user()->name }}! 👋
                        </h1>
                        <p class="text-xs text-white/90 font-medium">
                            Lokasi Kerja: <strong
                                class="underline decoration-amber-200">{{ $employee->location->name ?? 'Pos Parkir GB' }}</strong>
                        </p>
                    </div>

                    <div
                        class="hidden sm:block bg-white/10 backdrop-blur-md border border-white/20 rounded-2xl p-4 text-center min-w-[150px] relative z-10">
                        <p class="text-[9px] font-extrabold uppercase tracking-widest text-white/70">WAKTU SEKARANG</p>
                        <h2 id="live-clock" class="text-xl font-black tracking-tight my-0.5 font-mono">00:00:00</h2>
                        <p class="text-[10px] text-white/80 font-medium">
                            {{ \Carbon\Carbon::now()->isoFormat('D MMM YYYY') }}</p>
                    </div>
                </div>

                <!-- CARD DETAIL SHIFT HARI INI (1 KOLOM) -->
                <div
                    class="bg-slate-900 rounded-3xl p-6 text-white shadow-xl flex flex-col justify-between border border-slate-800 relative overflow-hidden">
                    <div class="flex items-center justify-between relative z-10">
                        <p class="text-[10px] font-extrabold uppercase tracking-widest text-orange-400">JADWAL SHIFT HARI
                            INI</p>
                        <span
                            class="bg-orange-500/20 text-orange-400 font-bold text-[10px] px-2.5 py-0.5 rounded-full border border-orange-500/30">
                            {{ $todayShift['name'] ?? 'Shift Regular' }}
                        </span>
                    </div>

                    <div class="my-3 relative z-10">
                        <div class="text-2xl font-black tracking-tight font-mono text-white flex items-center gap-2">
                            <i class="fa-regular fa-clock text-orange-400 text-xl"></i>
                            <span>{{ $todayShift['start_time'] ?? '08:00' }} -
                                {{ $todayShift['end_time'] ?? '17:00' }}</span>
                            <span class="text-xs font-normal text-slate-400">WIB</span>
                        </div>

                        @if (isset($todayShift['late_tolerance_minutes']))
                            <p class="text-[11px] font-semibold text-rose-400 mt-1 flex items-center gap-1">
                                <i class="fa-solid fa-triangle-exclamation text-xs"></i>
                                Toleransi Terlambat: Max {{ $todayShift['late_tolerance_minutes'] }} Menit
                            </p>
                        @else
                            <p class="text-[10px] text-slate-400 mt-1">Harap hadir 10 menit sebelum shift dimulai.</p>
                        @endif
                    </div>

                    <div
                        class="text-[10px] text-slate-400 border-t border-slate-800 pt-2 flex items-center justify-between relative z-10">
                        <span>Status Shift:</span>
                        <span class="font-bold text-emerald-400">Aktif & Berjalan</span>
                    </div>
                </div>
            </div>

            <!-- 2. WIDGET PRESENSI KEHADIRAN GPS -->
            <div class="bg-white rounded-3xl p-6 border border-slate-100 shadow-sm space-y-4">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="font-extrabold text-sm text-slate-800">Presensi Kehadiran GPS</h3>
                        <p class="text-xs text-slate-400 mt-0.5">Pastikan lokasi GPS kamu aktif sebelum melakukan presensi
                        </p>
                    </div>
                    <div id="gps-status"
                        class="bg-amber-50 text-amber-600 font-bold text-[11px] px-3 py-1 rounded-full flex items-center gap-1.5">
                        <i class="fa-solid fa-spinner fa-spin"></i>
                        <span>Mndeteksi GPS...</span>
                    </div>
                </div>

                <!-- ACTION BUTTONS GRID -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <!-- ABSEN MASUK -->
                    <div class="border border-slate-100 rounded-2xl p-4 flex items-center justify-between bg-slate-50/50">
                        <div class="flex items-center space-x-3.5">
                            <div
                                class="w-12 h-12 rounded-xl bg-emerald-100 text-emerald-600 flex items-center justify-center text-lg font-bold">
                                <i class="fa-solid fa-right-to-bracket"></i>
                            </div>
                            <div>
                                <h4 class="font-extrabold text-xs text-slate-800">Absen Masuk</h4>
                                <p class="text-[11px] text-slate-400 font-medium mt-0.5">
                                    Status:
                                    <span
                                        class="font-bold {{ $todayPresence && $todayPresence->time_in ? 'text-emerald-600' : 'text-slate-600' }}">
                                        {{ $todayPresence && $todayPresence->time_in ? \Carbon\Carbon::parse($todayPresence->time_in)->format('H:i') . ' WIB' : 'Belum Absen' }}
                                    </span>
                                </p>
                            </div>
                        </div>

                        @if (!$todayPresence || !$todayPresence->time_in)
                            <button id="btn-in" onclick="openCameraModal('in')"
                                class="px-5 py-2.5 bg-emerald-500 hover:bg-emerald-600 text-white font-extrabold rounded-xl text-xs shadow-lg shadow-emerald-500/20 transition flex items-center gap-2">
                                <i class="fa-solid fa-camera"></i>
                                <span>Presensi Masuk</span>
                            </button>
                        @else
                            <button disabled
                                class="px-5 py-2.5 bg-slate-100 text-emerald-600 font-extrabold rounded-xl text-xs flex items-center gap-2 cursor-default">
                                <i class="fa-solid fa-circle-check"></i>
                                <span>Sudah Masuk</span>
                            </button>
                        @endif
                    </div>

                    <!-- ABSEN PULANG -->
                    <div class="border border-slate-100 rounded-2xl p-4 flex items-center justify-between bg-slate-50/50">
                        <div class="flex items-center space-x-3.5">
                            <div
                                class="w-12 h-12 rounded-xl bg-rose-100 text-rose-600 flex items-center justify-center text-lg font-bold">
                                <i class="fa-solid fa-right-from-bracket"></i>
                            </div>
                            <div>
                                <h4 class="font-extrabold text-xs text-slate-800">Absen Pulang</h4>
                                <p class="text-[11px] text-slate-400 font-medium mt-0.5">
                                    Status:
                                    <span
                                        class="font-bold {{ $todayPresence && $todayPresence->time_out ? 'text-rose-600' : 'text-slate-600' }}">
                                        {{ $todayPresence && $todayPresence->time_out ? \Carbon\Carbon::parse($todayPresence->time_out)->format('H:i') . ' WIB' : '-- : --' }}
                                    </span>
                                </p>
                            </div>
                        </div>

                        @if ($todayPresence && $todayPresence->time_in && !$todayPresence->time_out)
                            <button id="btn-out" onclick="openCameraModal('out')"
                                class="px-5 py-2.5 bg-rose-500 hover:bg-rose-600 text-white font-extrabold rounded-xl text-xs shadow-lg shadow-rose-500/20 transition flex items-center gap-2">
                                <i class="fa-solid fa-camera"></i>
                                <span>Presensi Pulang</span>
                            </button>
                        @elseif($todayPresence && $todayPresence->time_out)
                            <button disabled
                                class="px-5 py-2.5 bg-slate-100 text-rose-600 font-extrabold rounded-xl text-xs flex items-center gap-2 cursor-default">
                                <i class="fa-solid fa-circle-check"></i>
                                <span>Sudah Pulang</span>
                            </button>
                        @else
                            <button disabled
                                class="px-5 py-2.5 bg-slate-200 text-slate-400 font-extrabold rounded-xl text-xs cursor-not-allowed flex items-center gap-2">
                                <i class="fa-solid fa-lock"></i>
                                <span>Presensi Pulang</span>
                            </button>
                        @endif
                    </div>
                </div>
            </div>

            <!-- 3. STAT CARDS GRID (DINAMIS SESUAI TIPE KARYAWAN) -->
            <div
                class="grid grid-cols-1 sm:grid-cols-2 {{ ($employee->employee_type ?? 'Tetap') === 'Harian' ? 'lg:grid-cols-3' : 'lg:grid-cols-4' }} gap-4">

                <!-- CARD 1: HADIR BULAN INI -->
                <div class="bg-white rounded-3xl p-5 border border-slate-100 shadow-sm flex items-center justify-between">
                    <div>
                        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">HADIR BULAN INI</p>
                        <h2 class="text-2xl font-black text-slate-800 mt-1">{{ $monthlyPresenceCount }} <span
                                class="text-xs font-bold text-slate-400">Hari</span></h2>
                        <p class="text-[10px] font-bold text-emerald-500 mt-1 flex items-center gap-1">
                            <i class="fa-solid fa-circle-check"></i> Ter-record Sistem
                        </p>
                    </div>
                    <div
                        class="w-11 h-11 rounded-2xl bg-blue-50 text-blue-500 flex items-center justify-center text-base font-bold">
                        <i class="fa-solid fa-user-check"></i>
                    </div>
                </div>

                <!-- CARD 2: TERLAMBAT -->
                <div class="bg-white rounded-3xl p-5 border border-slate-100 shadow-sm flex items-center justify-between">
                    <div>
                        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">TERLAMBAT</p>
                        <h2 class="text-2xl font-black text-slate-800 mt-1">{{ $lateCount }} <span
                                class="text-xs font-bold text-slate-400">Kali</span></h2>
                        <p
                            class="text-[10px] font-bold {{ $lateCount == 0 ? 'text-emerald-500' : 'text-amber-500' }} mt-1">
                            {{ $lateCount == 0 ? 'Disiplin Sangat Baik' : 'Tingkatkan Disiplin' }}
                        </p>
                    </div>
                    <div
                        class="w-11 h-11 rounded-2xl bg-amber-50 text-amber-500 flex items-center justify-center text-base font-bold">
                        <i class="fa-solid fa-clock-rotate-left"></i>
                    </div>
                </div>

                <!-- CARD 3: HANYA MUNCUL UNTUK KONTRAK & TETAP (HARIAN DISEMBUNYIKAN) -->
                @if (($employee->employee_type ?? 'Tetap') === 'Kontrak')
                    @php
                        $daysLeft = $employee->contract_end_date
                            ? \Carbon\Carbon::now()->diffInDays($employee->contract_end_date, false)
                            : null;
                    @endphp
                    <div
                        class="bg-white rounded-3xl p-5 border border-slate-100 shadow-sm flex items-center justify-between">
                        <div>
                            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">BATAS KONTRAK</p>
                            <h2
                                class="text-xl font-black {{ $daysLeft !== null && $daysLeft <= 30 ? 'text-amber-500' : 'text-slate-800' }} mt-1">
                                {{ $daysLeft !== null ? ($daysLeft >= 0 ? ceil($daysLeft) . ' Hari' : 'Expired') : '-' }}
                            </h2>
                            <p class="text-[10px] font-bold text-slate-400 mt-1 truncate max-w-[120px]">
                                s.d
                                {{ $employee->contract_end_date ? $employee->contract_end_date->isoFormat('D MMM YYYY') : 'Tidak Diatur' }}
                            </p>
                        </div>
                        <div
                            class="w-11 h-11 rounded-2xl bg-amber-50 text-amber-500 flex items-center justify-center text-base font-bold shrink-0">
                            <i class="fa-solid fa-file-contract"></i>
                        </div>
                    </div>
                @elseif(($employee->employee_type ?? 'Tetap') === 'Tetap')
                    <div
                        class="bg-white rounded-3xl p-5 border border-slate-100 shadow-sm flex items-center justify-between">
                        <div>
                            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">SISA CUTI</p>
                            <h2 class="text-2xl font-black text-slate-800 mt-1">{{ $employee->leave_quota ?? 12 }} <span
                                    class="text-xs font-bold text-slate-400">Hari</span></h2>
                            <p class="text-[10px] font-bold text-slate-400 mt-1">Hak Cuti Tahunan</p>
                        </div>
                        <div
                            class="w-11 h-11 rounded-2xl bg-purple-50 text-purple-500 flex items-center justify-center text-base font-bold shrink-0">
                            <i class="fa-solid fa-calendar-days"></i>
                        </div>
                    </div>
                @endif

                <!-- CARD 4: SLIP GAJI TERAKHIR -->
                <div class="bg-white p-5 rounded-3xl border border-slate-100 shadow-sm flex items-center justify-between">
                    <div>
                        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1">SLIP GAJI TERAKHIR</p>
                        @if ($latestPayroll)
                            <h3 class="text-lg font-extrabold text-slate-800 font-mono">
                                Rp {{ number_format($latestPayroll->net_salary, 0, ',', '.') }}
                            </h3>
                            <a href="{{ route('payrolls.print', $latestPayroll->id) }}" target="_blank"
                                class="text-xs font-bold text-[#FF6B00] hover:underline mt-1 inline-block">
                                Lihat Slip &rarr;
                            </a>
                        @else
                            <h3 class="text-sm font-bold text-slate-400">Belum Ada</h3>
                            <span class="text-xs text-slate-400 mt-1 block">-</span>
                        @endif
                    </div>
                    <div
                        class="w-11 h-11 rounded-2xl bg-emerald-50 text-emerald-500 flex items-center justify-center text-base font-bold">
                        <i class="fa-solid fa-file-invoice-dollar"></i>
                    </div>
                </div>

            </div>

            <!-- 4. RIWAYAT ABSENSI & INFORMASI SAYA -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- COL 1: TABEL RIWAYAT (2 KOLOM GRID) -->
                <div class="lg:col-span-2 bg-white rounded-3xl p-6 border border-slate-100 shadow-sm space-y-4">
                    <div class="flex items-center justify-between">
                        <h3 class="font-extrabold text-sm text-slate-800">Riwayat Absensi Terakhir</h3>
                        <a href="#" class="text-xs font-bold text-[#FF6B00] hover:underline">Lihat Semua</a>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr
                                    class="border-b border-slate-100 text-[10px] font-bold text-slate-400 uppercase tracking-wider">
                                    <th class="pb-3">TANGGAL</th>
                                    <th class="pb-3">JAM MASUK</th>
                                    <th class="pb-3">JAM PULANG</th>
                                    <th class="pb-3 text-right">STATUS</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-50 text-xs font-medium text-slate-700">
                                @forelse($recentPresences as $p)
                                    <tr>
                                        <td class="py-3.5 font-bold">
                                            {{ \Carbon\Carbon::parse($p->date)->isToday() ? 'Hari Ini' : (\Carbon\Carbon::parse($p->date)->isYesterday() ? 'Kemarin' : \Carbon\Carbon::parse($p->date)->isoFormat('D MMM YYYY')) }}
                                        </td>
                                        <td
                                            class="py-3.5 {{ $p->time_in ? 'text-emerald-600 font-bold' : 'text-slate-400' }}">
                                            {{ $p->time_in ? \Carbon\Carbon::parse($p->time_in)->format('H:i') . ' WIB' : '-- : --' }}
                                        </td>
                                        <td class="py-3.5 text-slate-600">
                                            {{ $p->time_out ? \Carbon\Carbon::parse($p->time_out)->format('H:i') . ' WIB' : '-- : --' }}
                                        </td>
                                        <td class="py-3.5 text-right">
                                            <span
                                                class="px-2.5 py-1 rounded-full text-[10px] font-bold {{ $p->status == 'Hadir' || $p->status == 'Tepat Waktu' ? 'bg-emerald-100 text-emerald-600' : ($p->status == 'Terlambat' ? 'bg-amber-100 text-amber-600' : 'bg-slate-100 text-slate-500') }}">
                                                {{ $p->status ?? 'Hadir' }}
                                            </span>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="py-6 text-center text-slate-400 text-xs">Belum ada
                                            riwayat absensi.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- INFORMASI SAYA -->
                <div class="bg-white rounded-3xl p-6 border border-slate-100 shadow-sm space-y-5">
                    <h3 class="font-extrabold text-sm text-slate-800">Informasi Saya</h3>

                    <div class="flex items-center space-x-3.5 pb-4 border-b border-slate-100">
                        <div
                            class="w-12 h-12 rounded-2xl bg-orange-100 text-[#FF6B00] font-black flex items-center justify-center text-sm shrink-0">
                            {{ strtoupper(substr($employee->name ?? Auth::user()->name, 0, 2)) }}
                        </div>
                        <div class="overflow-hidden">
                            <h4 class="font-extrabold text-sm text-slate-800 truncate">
                                {{ $employee->name ?? Auth::user()->name }}
                            </h4>
                            <p class="text-xs text-slate-400 font-medium mt-0.5 truncate">
                                {{ $employee->email ?? Auth::user()->email }}
                            </p>
                        </div>
                    </div>

                    <div class="space-y-3 text-xs">
                        <!-- MENAMPILKAN STATUS KERJA / TIPE KARYAWAN -->
                        <div class="flex justify-between items-center">
                            <span class="text-slate-400 font-medium">Status Kerja</span>
                            <span
                                class="px-2.5 py-0.5 rounded-full text-[10px] font-bold
                                {{ ($employee->employee_type ?? 'Tetap') === 'Tetap'
                                    ? 'bg-blue-100 text-blue-600'
                                    : (($employee->employee_type ?? 'Tetap') === 'Kontrak'
                                        ? 'bg-amber-100 text-amber-600'
                                        : 'bg-purple-100 text-purple-600') }}">
                                {{ $employee->employee_type ?? 'Tetap' }}
                            </span>
                        </div>

                        <div class="flex justify-between items-center">
                            <span class="text-slate-400 font-medium">Jabatan</span>
                            <span
                                class="font-bold text-slate-700 text-right">{{ $employee->position->name ?? '-' }}</span>
                        </div>

                        <div class="flex justify-between items-center">
                            <span class="text-slate-400 font-medium">Departemen</span>
                            <span class="font-bold text-slate-700 text-right">
                                {{ $employee->department->name ?? ($employee->position->department->name ?? 'Belum Diatur') }}
                            </span>
                        </div>

                        <div class="flex justify-between items-center">
                            <span class="text-slate-400 font-medium">Lokasi Kerja</span>
                            <span
                                class="font-bold text-slate-700 text-right">{{ $employee->location->name ?? '-' }}</span>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>

    <!-- MODAL KAMERA POPUP -->
    <div id="camera-modal"
        class="fixed inset-0 z-50 hidden bg-slate-900/60 backdrop-blur-sm flex items-center justify-center p-4">
        <div class="bg-white rounded-3xl max-w-md w-full p-6 space-y-4 shadow-2xl relative">
            <div class="flex items-center justify-between border-b border-slate-100 pb-3">
                <h3 id="modal-title" class="font-black text-sm text-slate-800">Presensi Karyawan</h3>
                <button onclick="closeCameraModal()"
                    class="text-slate-400 hover:text-slate-600 font-bold text-lg">&times;</button>
            </div>

            <!-- CAMERA VIEWPORT -->
            <div class="relative overflow-hidden rounded-2xl bg-black aspect-video flex items-center justify-center">
                <div id="my_camera" class="w-full h-full"></div>
            </div>

            <!-- SUBMIT BUTTON -->
            <button id="btn-submit-presence" onclick="submitPresence()"
                class="w-full py-3 bg-[#FF6B00] hover:bg-orange-600 text-white font-extrabold rounded-xl text-xs shadow-lg shadow-orange-500/20 transition flex items-center justify-center gap-2">
                <i class="fa-solid fa-camera"></i>
                <span>Ambil Foto & Absen</span>
            </button>
        </div>
    </div>

    <!-- WEBCAM JS & SCRIPT PROCESS -->
    @push('scripts')
        <script src="https://cdnjs.cloudflare.com/ajax/libs/webcamjs/1.0.26/webcam.min.js"></script>
        <script>
            // Realtime Clock
            setInterval(() => {
                const now = new Date();
                document.getElementById('live-clock').innerText = now.toLocaleTimeString('id-ID');
            }, 1000);

            let userLat = null;
            let userLong = null;
            let currentType = 'in';

            // Get GPS Location
            if (navigator.geolocation) {
                navigator.geolocation.watchPosition(
                    function(position) {
                        userLat = position.coords.latitude;
                        userLong = position.coords.longitude;
                        document.getElementById('gps-status').className =
                            "bg-emerald-50 text-emerald-600 font-bold text-[11px] px-3 py-1 rounded-full flex items-center gap-1.5";
                        document.getElementById('gps-status').innerHTML = `
                            <i class="fa-solid fa-circle-check text-xs"></i>
                            <span>GPS Aktif</span>
                        `;
                    },
                    function(error) {
                        document.getElementById('gps-status').className =
                            "bg-rose-50 text-rose-600 font-bold text-[11px] px-3 py-1 rounded-full flex items-center gap-1.5";
                        document.getElementById('gps-status').innerHTML = `
                            <i class="fa-solid fa-circle-xmark text-xs"></i>
                            <span>GPS Tidak Aktif</span>
                        `;
                    }, {
                        enableHighAccuracy: true
                    }
                );
            }

            function openCameraModal(type) {
                if (!userLat || !userLong) {
                    alert('Lokasi GPS belum terdeteksi. Harap izinkan akses lokasi di browser kamu!');
                    return;
                }

                currentType = type;
                document.getElementById('modal-title').innerText = type === 'in' ? 'Presensi Masuk (Selfie)' :
                    'Presensi Pulang (Selfie)';
                document.getElementById('camera-modal').classList.remove('hidden');

                Webcam.set({
                    width: 440,
                    height: 330,
                    image_format: 'jpeg',
                    jpeg_quality: 80,
                    flip_horiz: true
                });
                Webcam.attach('#my_camera');
            }

            function closeCameraModal() {
                Webcam.reset();
                document.getElementById('camera-modal').classList.add('hidden');
            }

            function submitPresence() {
                const btn = document.getElementById('btn-submit-presence');
                btn.disabled = true;
                btn.innerText = 'Memproses...';

                Webcam.snap(function(data_uri) {
                    fetch("{{ route('presence.store') }}", {
                            method: "POST",
                            headers: {
                                "Content-Type": "application/json",
                                "X-CSRF-TOKEN": "{{ csrf_token() }}"
                            },
                            body: JSON.stringify({
                                image: data_uri,
                                lat: userLat,
                                long: userLong,
                                type: currentType
                            })
                        })
                        .then(res => res.json())
                        .then(data => {
                            btn.disabled = false;
                            btn.innerText = 'Ambil Foto & Absen';

                            if (data.success) {
                                alert(data.message);
                                closeCameraModal();
                                location.reload();
                            } else {
                                alert(data.message);
                            }
                        })
                        .catch(err => {
                            btn.disabled = false;
                            btn.innerText = 'Ambil Foto & Absen';
                            alert('Terjadi kesalahan koneksi/server.');
                        });
                });
            }
        </script>
    @endpush
@endsection
