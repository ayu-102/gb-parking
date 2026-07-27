@extends('layouts.karyawan')

@section('content')
    <div class="space-y-6">

        @if (isset($message) && !$employee)
            <div class="bg-rose-50 border border-rose-200 text-rose-700 px-4 py-3 rounded-2xl text-xs font-bold">
                <i class="fa-solid fa-triangle-exclamation mr-1"></i> {{ $message }}
            </div>
        @else
            <!-- HEADER INFO -->
            <div
                class="flex flex-col md:flex-row md:items-center justify-between gap-4 bg-white p-6 rounded-3xl border border-slate-100 shadow-sm">
                <div>
                    <span
                        class="text-[10px] font-bold text-[#FF6B00] bg-orange-50 px-3 py-1 rounded-full uppercase tracking-wider">
                        GEOFENCING RADAR GPS
                    </span>
                    <h1 class="text-xl font-black text-slate-800 tracking-tight mt-2">
                        Peta Radius Absensi Live GPS
                    </h1>
                    <p class="text-xs text-slate-400 mt-1">
                        Posisi kamu akan dideteksi secara real-time terhadap lokasi penempatan:
                        <strong class="text-slate-700">{{ $employee->location->name ?? 'Belum Ditentukan' }}</strong>
                    </p>
                </div>

                <!-- INDIKATOR STATUS RADAR -->
                <div id="geofence-status"
                    class="bg-slate-100 text-slate-500 text-xs font-bold px-4 py-3 rounded-2xl flex items-center gap-2">
                    <i class="fa-solid fa-spinner fa-spin"></i>
                    <span>Menghitung Jarak GPS...</span>
                </div>
            </div>

            <!-- GRID PETA & PANEL CONTROL -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

                <!-- KIRI: CONTAINER PETA INTERAKTIF (2 KOLOM) -->
                <div class="lg:col-span-2 bg-white rounded-3xl p-4 border border-slate-100 shadow-sm space-y-3">
                    <div id="map" class="w-full h-[480px] rounded-2xl z-10 border border-slate-100"></div>

                    <div class="flex items-center justify-between text-[11px] text-slate-400 font-semibold px-2">
                        <span class="flex items-center gap-1.5"><span
                                class="w-3 h-3 rounded-full bg-blue-500 inline-block"></span> Posisi Anda</span>
                        <span class="flex items-center gap-1.5"><span
                                class="w-3 h-3 rounded-full bg-red-500 inline-block"></span> Titik Pos Parkir</span>
                        <span class="flex items-center gap-1.5"><span
                                class="w-3 h-3 rounded-full bg-orange-400/30 border border-orange-500 inline-block"></span>
                            Radius Boleh Absen</span>
                    </div>
                </div>

                <!-- KANAN: PANEL CONTROL & ACTION ABSEN -->
                <div
                    class="bg-white rounded-3xl p-6 border border-slate-100 shadow-sm flex flex-col justify-between space-y-6">
                    <div class="space-y-4">
                        <h3 class="font-extrabold text-sm text-slate-800 border-b border-slate-100 pb-3">Informasi Koordinat
                        </h3>

                        <div class="bg-slate-50 p-4 rounded-2xl space-y-3 text-xs">
                            <div class="flex justify-between items-center">
                                <span class="text-slate-400 font-medium">Batas Radius:</span>
                                <span class="font-extrabold text-slate-800">{{ $employee->location->radius ?? 100 }}
                                    Meter</span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-slate-400 font-medium">Jarak Kamu:</span>
                                <span id="text-distance" class="font-extrabold text-[#FF6B00]">-- Meter</span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-slate-400 font-medium">Status Izin:</span>
                                <span id="text-permission" class="font-bold text-slate-400">Memeriksa...</span>
                            </div>
                        </div>

                        <div class="border-t border-slate-100 pt-4 space-y-3">
                            <h4 class="font-extrabold text-xs text-slate-800">Status Presensi Hari Ini</h4>
                            <div class="grid grid-cols-2 gap-2 text-center text-xs">
                                <div class="bg-emerald-50 p-3 rounded-xl border border-emerald-100">
                                    <p class="text-[10px] text-emerald-600 font-bold uppercase">Masuk</p>
                                    <p class="font-black text-slate-800 mt-1">
                                        {{ $todayPresence && $todayPresence->time_in ? \Carbon\Carbon::parse($todayPresence->time_in)->format('H:i') . ' WIB' : '--:--' }}
                                    </p>
                                </div>
                                <div class="bg-rose-50 p-3 rounded-xl border border-rose-100">
                                    <p class="text-[10px] text-rose-600 font-bold uppercase">Pulang</p>
                                    <p class="font-black text-slate-800 mt-1">
                                        {{ $todayPresence && $todayPresence->time_out ? \Carbon\Carbon::parse($todayPresence->time_out)->format('H:i') . ' WIB' : '--:--' }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- TOMBOL AKSI PRESENSI -->
                    <div class="space-y-2">
                        @if (!$todayPresence || !$todayPresence->time_in)
                            <button id="btn-quick-in" onclick="openCameraModal('in')" disabled
                                class="w-full py-3.5 bg-slate-200 text-slate-400 font-extrabold rounded-2xl text-xs shadow-none transition flex items-center justify-center gap-2 cursor-not-allowed">
                                <i class="fa-solid fa-camera"></i>
                                <span>Presensi Masuk Sekarang</span>
                            </button>
                        @elseif($todayPresence && $todayPresence->time_in && !$todayPresence->time_out)
                            <button id="btn-quick-out" onclick="openCameraModal('out')" disabled
                                class="w-full py-3.5 bg-slate-200 text-slate-400 font-extrabold rounded-2xl text-xs shadow-none transition flex items-center justify-center gap-2 cursor-not-allowed">
                                <i class="fa-solid fa-camera"></i>
                                <span>Presensi Pulang Sekarang</span>
                            </button>
                        @else
                            <div
                                class="bg-emerald-50 text-emerald-600 p-3.5 rounded-2xl text-center text-xs font-bold border border-emerald-100">
                                <i class="fa-solid fa-circle-check mr-1"></i> Presensi Hari Ini Selesai
                            </div>
                        @endif
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
            <div class="relative overflow-hidden rounded-2xl bg-black aspect-video flex items-center justify-center">
                <div id="my_camera" class="w-full h-full"></div>
            </div>
            <button id="btn-submit-presence" onclick="submitPresence()"
                class="w-full py-3 bg-[#FF6B00] hover:bg-orange-600 text-white font-extrabold rounded-xl text-xs shadow-lg shadow-orange-500/20 transition flex items-center justify-center gap-2">
                <i class="fa-solid fa-camera"></i>
                <span>Ambil Foto & Absen</span>
            </button>
        </div>
    </div>

    @push('scripts')
        <script src="https://cdnjs.cloudflare.com/ajax/libs/webcamjs/1.0.26/webcam.min.js"></script>
        <script>
            // Data Koordinat Lokasi Pos dari DB
            const officeLat = {{ $employee->location->latitude ?? -6.175392 }};
            const officeLong = {{ $employee->location->longitude ?? 106.827153 }};
            const allowedRadius = {{ $employee->location->radius ?? 100 }}; // Meter

            let map, userMarker, officeMarker, radiusCircle;
            let userLat = null;
            let userLong = null;
            let currentType = 'in';
            let isInsideRadius = false;

            // Inisialisasi Leaflet Map
            document.addEventListener("DOMContentLoaded", function() {
                map = L.map('map').setView([officeLat, officeLong], 16);

                // Tile Layer OpenStreetMap
                L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                    maxZoom: 19,
                    attribution: '© OpenStreetMap'
                }).addTo(map);

                // Marker Pos Parkir (Merah)
                const redIcon = L.icon({
                    iconUrl: 'https://raw.githubusercontent.com/pointhi/leaflet-color-markers/master/img/marker-icon-red.png',
                    shadowUrl: 'https://cdnjs.cloudflare.com/ajax/libs/leaflet/0.7.7/images/marker-shadow.png',
                    iconSize: [25, 41],
                    iconAnchor: [12, 41],
                    popupAnchor: [1, -34],
                    shadowSize: [41, 41]
                });

                officeMarker = L.marker([officeLat, officeLong], {
                        icon: redIcon
                    }).addTo(map)
                    .bindPopup("<b>{{ $employee->location->name ?? 'Pos Parkir' }}</b><br>Titik Lokasi Resmi")
                    .openPopup();

                // Lingkaran Radius (Geofence Circle)
                radiusCircle = L.circle([officeLat, officeLong], {
                    color: '#FF6B00',
                    fillColor: '#FF6B00',
                    fillOpacity: 0.15,
                    radius: allowedRadius
                }).addTo(map);

                // Start GPS Tracking HP Karyawan
                trackUserLocation();
            });

            // Rumus Haversine JS untuk hitung jarak real-time di Map
            function getDistanceInMeters(lat1, lon1, lat2, lon2) {
                const R = 6371000; // Meter
                const dLat = (lat2 - lat1) * Math.PI / 180;
                const dLon = (lon2 - lon1) * Math.PI / 180;
                const a = Math.sin(dLat / 2) * Math.sin(dLat / 2) +
                    Math.cos(lat1 * Math.PI / 180) * Math.cos(lat2 * Math.PI / 180) *
                    Math.sin(dLon / 2) * Math.sin(dLon / 2);
                const c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1 - a));
                return R * c;
            }

            function trackUserLocation() {
                if (navigator.geolocation) {
                    navigator.geolocation.watchPosition(function(position) {
                        userLat = position.coords.latitude;
                        userLong = position.coords.longitude;

                        // Marker Biru untuk Posisi Karyawan
                        const blueIcon = L.icon({
                            iconUrl: 'https://raw.githubusercontent.com/pointhi/leaflet-color-markers/master/img/marker-icon-blue.png',
                            shadowUrl: 'https://cdnjs.cloudflare.com/ajax/libs/leaflet/0.7.7/images/marker-shadow.png',
                            iconSize: [25, 41],
                            iconAnchor: [12, 41],
                            popupAnchor: [1, -34],
                            shadowSize: [41, 41]
                        });

                        if (userMarker) {
                            userMarker.setLatLng([userLat, userLong]);
                        } else {
                            userMarker = L.marker([userLat, userLong], {
                                    icon: blueIcon
                                }).addTo(map)
                                .bindPopup("<b>Posisi Kamu Sekarang</b>");
                        }

                        // Hitung Jarak Realtime
                        const distance = Math.round(getDistanceInMeters(userLat, userLong, officeLat, officeLong));
                        document.getElementById('text-distance').innerText = distance + " Meter";

                        const statusBadge = document.getElementById('geofence-status');
                        const textPerm = document.getElementById('text-permission');
                        const btnIn = document.getElementById('btn-quick-in');
                        const btnOut = document.getElementById('btn-quick-out');

                        if (distance <= allowedRadius) {
                            isInsideRadius = true;
                            statusBadge.className =
                                "bg-emerald-50 text-emerald-600 text-xs font-bold px-4 py-3 rounded-2xl flex items-center gap-2";
                            statusBadge.innerHTML =
                                `<i class="fa-solid fa-circle-check"></i> <span>Dalam Radius Absen</span>`;

                            textPerm.className = "font-extrabold text-emerald-600";
                            textPerm.innerText = "Diizinkan Absen";

                            // Enable Tombol Absen
                            if (btnIn) {
                                btnIn.disabled = false;
                                btnIn.className =
                                    "w-full py-3.5 bg-emerald-500 hover:bg-emerald-600 text-white font-extrabold rounded-2xl text-xs shadow-lg shadow-emerald-500/20 transition flex items-center justify-center gap-2 cursor-pointer";
                            }
                            if (btnOut) {
                                btnOut.disabled = false;
                                btnOut.className =
                                    "w-full py-3.5 bg-rose-500 hover:bg-rose-600 text-white font-extrabold rounded-2xl text-xs shadow-lg shadow-rose-500/20 transition flex items-center justify-center gap-2 cursor-pointer";
                            }
                        } else {
                            isInsideRadius = false;
                            statusBadge.className =
                                "bg-rose-50 text-rose-600 text-xs font-bold px-4 py-3 rounded-2xl flex items-center gap-2";
                            statusBadge.innerHTML =
                                `<i class="fa-solid fa-circle-xmark"></i> <span>Di Luar Radius (${distance}m)</span>`;

                            textPerm.className = "font-extrabold text-rose-600";
                            textPerm.innerText = "Di Luar Jangkauan";

                            // Disable Tombol Absen
                            if (btnIn) {
                                btnIn.disabled = true;
                                btnIn.className =
                                    "w-full py-3.5 bg-slate-200 text-slate-400 font-extrabold rounded-2xl text-xs shadow-none transition flex items-center justify-center gap-2 cursor-not-allowed";
                            }
                            if (btnOut) {
                                btnOut.disabled = true;
                                btnOut.className =
                                    "w-full py-3.5 bg-slate-200 text-slate-400 font-extrabold rounded-2xl text-xs shadow-none transition flex items-center justify-center gap-2 cursor-not-allowed";
                            }
                        }

                    }, function(error) {
                        alert("Gagal membaca GPS. Izinkan lokasi di browser kamu!");
                    }, {
                        enableHighAccuracy: true
                    });
                }
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
                    flip_horiz: true // <-- Ubah jadi TRUE jika tampilan default kamera laptop kamu sudah terbalik secara hardware
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
                            alert('Terjadi kesalahan koneksi.');
                        });
                });
            }
        </script>
    @endpush
@endsection
