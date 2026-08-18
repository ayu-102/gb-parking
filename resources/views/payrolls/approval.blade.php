<x-app-layout>

    <style>
        @keyframes modalShow {
            from {
                opacity: 0;
                transform: scale(0.9) translateY(10px);
            }

            to {
                opacity: 1;
                transform: scale(1) translateY(0);
            }
        }

        @keyframes modalHide {
            from {
                opacity: 1;
                transform: scale(1) translateY(0);
            }

            to {
                opacity: 0;
                transform: scale(0.9) translateY(10px);
            }
        }

        .animate-modal-show {
            animation: modalShow 0.25s cubic-bezier(0.16, 1, 0.3, 1) forwards;
        }

        .animate-modal-hide {
            animation: modalHide 0.2s cubic-bezier(0.16, 1, 0.3, 1) forwards;
        }
    </style>

    <div class="space-y-6">

        <!-- HEADER PAGE -->
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div>
                <h1 class="text-2xl font-bold text-slate-800 tracking-tight">Approval Payroll</h1>
                <p class="text-xs text-slate-500 mt-1">Review dan berikan persetujuan persentase penggajian sebelum
                    diterbitkan menjadi Slip Gaji resmi.</p>
            </div>
            <div class="flex items-center space-x-2">
                <a href="{{ route('payrolls.index') }}"
                    class="px-4 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold rounded-xl text-xs flex items-center space-x-2 transition">
                    <i class="fa-solid fa-arrow-left text-xs"></i>
                    <span>Kembali ke Process</span>
                </a>
            </div>
        </div>

        <!-- NOTIFIKASI SUKSES / ERROR -->
        @if (session('success'))
            <div
                class="p-4 bg-emerald-50 border border-emerald-200 text-emerald-700 text-xs font-semibold rounded-2xl flex items-center space-x-2 shadow-sm">
                <i class="fa-solid fa-circle-check text-base text-emerald-500"></i>
                <span>{{ session('success') }}</span>
            </div>
        @endif

        @if (session('error'))
            <div
                class="p-4 bg-rose-50 border border-rose-200 text-rose-700 text-xs font-semibold rounded-2xl flex items-center space-x-2 shadow-sm">
                <i class="fa-solid fa-circle-xmark text-base text-rose-500"></i>
                <span>{{ session('error') }}</span>
            </div>
        @endif

        <!-- STEPPER PROCESS TRACKER (GB PARKING STYLE - STEP 4 ACTIVE) -->
        <div class="bg-white p-5 rounded-2xl border border-slate-100 shadow-sm overflow-x-auto">
            <div class="flex items-center justify-between min-w-[700px] text-xs">
                <!-- Step 1 -->
                <div class="flex items-center space-x-3">
                    <div
                        class="w-8 h-8 rounded-full bg-emerald-500 text-white flex items-center justify-center font-bold text-xs shadow-md shadow-emerald-500/20">
                        <i class="fa-solid fa-check"></i>
                    </div>
                    <div>
                        <p class="font-bold text-slate-800 text-[11px]">1. Pilih Periode</p>
                        <p class="text-[10px] text-emerald-600 font-semibold">Selesai</p>
                    </div>
                </div>
                <div class="h-[2px] bg-emerald-300 flex-1 mx-3"></div>

                <!-- Step 2 -->
                <div class="flex items-center space-x-3">
                    <div
                        class="w-8 h-8 rounded-full bg-emerald-500 text-white flex items-center justify-center font-bold text-xs shadow-md shadow-emerald-500/20">
                        <i class="fa-solid fa-check"></i>
                    </div>
                    <div>
                        <p class="font-bold text-slate-800 text-[11px]">2. Hitung & Validasi</p>
                        <p class="text-[10px] text-emerald-600 font-semibold">Selesai</p>
                    </div>
                </div>
                <div class="h-[2px] bg-emerald-300 flex-1 mx-3"></div>

                <!-- Step 3 -->
                <div class="flex items-center space-x-3">
                    <div
                        class="w-8 h-8 rounded-full bg-emerald-500 text-white flex items-center justify-center font-bold text-xs shadow-md shadow-emerald-500/20">
                        <i class="fa-solid fa-check"></i>
                    </div>
                    <div>
                        <p class="font-bold text-slate-800 text-[11px]">3. Review Payroll</p>
                        <p class="text-[10px] text-emerald-600 font-semibold">Selesai</p>
                    </div>
                </div>
                <div class="h-[2px] bg-emerald-300 flex-1 mx-3"></div>

                <!-- Step 4 (ACTIVE) -->
                <div class="flex items-center space-x-3">
                    <div
                        class="w-8 h-8 rounded-full bg-[#FF6B00] text-white flex items-center justify-center font-bold text-xs shadow-md shadow-orange-500/20">
                        4
                    </div>
                    <div>
                        <p class="font-bold text-slate-800 text-[11px]">4. Approval Gaji</p>
                        <p class="text-[10px] text-orange-500 font-semibold">Menunggu Persetujuan</p>
                    </div>
                </div>
                <div class="h-[2px] bg-slate-200 flex-1 mx-3"></div>

                <!-- Step 5 -->
                <div class="flex items-center space-x-3">
                    <div
                        class="w-8 h-8 rounded-full bg-slate-100 text-slate-400 flex items-center justify-center font-bold text-xs">
                        5
                    </div>
                    <div>
                        <p class="font-bold text-slate-400 text-[11px]">5. Selesai / Slip</p>
                        <p class="text-[10px] text-slate-400">Belum Diproses</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- STAT CARDS (TOP SUMMARY) -->
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
            <div class="bg-white p-4 rounded-2xl border border-slate-100 shadow-sm flex items-center space-x-3">
                <div
                    class="w-10 h-10 rounded-xl bg-purple-50 text-purple-600 flex items-center justify-center font-bold text-sm">
                    <i class="fa-solid fa-calendar-check"></i>
                </div>
                <div>
                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Periode Approval</p>
                    <h4 class="text-sm font-extrabold text-slate-800">
                        {{ \Carbon\Carbon::parse($selectedMonth)->translatedFormat('F Y') }}
                    </h4>
                </div>
            </div>

            <div class="bg-white p-4 rounded-2xl border border-slate-100 shadow-sm flex items-center space-x-3">
                <div
                    class="w-10 h-10 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center font-bold text-sm">
                    <i class="fa-solid fa-users"></i>
                </div>
                <div>
                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Total Pengajuan</p>
                    <h4 class="text-sm font-extrabold text-slate-800">
                        {{ $payrolls->total() }} <span class="text-xs text-slate-400 font-normal">Karyawan</span>
                    </h4>
                </div>
            </div>

            <div class="bg-white p-4 rounded-2xl border border-slate-100 shadow-sm flex items-center space-x-3">
                <div
                    class="w-10 h-10 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center font-bold text-sm">
                    <i class="fa-solid fa-hand-holding-dollar"></i>
                </div>
                <div>
                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Total Nominal THP</p>
                    <h4 class="text-sm font-extrabold text-emerald-600 font-mono">
                        Rp {{ number_format($payrolls->sum('net_salary'), 0, ',', '.') }}
                    </h4>
                </div>
            </div>

            <div class="bg-white p-4 rounded-2xl border border-slate-100 shadow-sm flex items-center space-x-3">
                <div
                    class="w-10 h-10 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center font-bold text-sm">
                    <i class="fa-solid fa-user-shield"></i>
                </div>
                <div>
                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Verifikasi Otoritas</p>
                    <span
                        class="px-2 py-0.5 bg-amber-50 text-amber-600 border border-amber-200 font-extrabold rounded-md text-[10px] uppercase">
                        Otorisasi Atasan
                    </span>
                </div>
            </div>
        </div>

        <!-- MAIN GRID LAYOUT -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 items-start">

            <!-- TABEL DAFTAR APPROVAL (KIRI 2 KOLOM) -->
            <div class="lg:col-span-2 space-y-4">
                <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">

                    <!-- FILTER BAR -->
                    <div class="p-4 border-b border-slate-100 bg-slate-50/50 flex items-center justify-between gap-3">
                        <form method="GET" action="{{ route('payrolls.approval') }}"
                            class="flex items-center space-x-2">
                            <input type="month" name="month" value="{{ $selectedMonth }}"
                                class="px-3.5 py-2 rounded-xl border border-slate-200 text-xs font-bold focus:outline-none focus:border-[#FF6B00] bg-white text-slate-700">
                            <button type="submit"
                                class="px-4 py-2 bg-slate-800 hover:bg-slate-900 text-white font-bold rounded-xl text-xs transition">
                                Filter Periode
                            </button>
                        </form>
                    </div>

                    <!-- TABEL DATA APPROVAL -->
                    <div class="overflow-x-auto">
                        <table class="w-full text-left text-xs">
                            <thead
                                class="bg-slate-50 text-slate-400 font-bold border-b border-slate-100 uppercase tracking-wider">
                                <tr>
                                    <th class="p-4">Karyawan</th>
                                    <th class="p-4">Gaji Pokok</th>
                                    <th class="p-4">Pendapatan</th>
                                    <th class="p-4">Potongan</th>
                                    <th class="p-4">Take Home Pay</th>
                                    <th class="p-4 text-center">Keputusan (Aksi)</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100 text-slate-700">
                                @forelse ($payrolls as $item)
                                    <tr class="hover:bg-slate-50/80 transition">
                                        <!-- Karyawan -->
                                        <td class="p-4 whitespace-nowrap">
                                            <div class="flex items-center space-x-3">
                                                <div
                                                    class="w-8 h-8 rounded-full bg-slate-100 border border-slate-200 flex items-center justify-center font-bold text-slate-600 text-xs uppercase">
                                                    {{ substr($item->employee->name ?? 'K', 0, 2) }}
                                                </div>
                                                <div>
                                                    <p class="font-bold text-slate-800">
                                                        {{ $item->employee->name ?? '-' }}</p>
                                                    <p class="text-[10px] text-slate-400 font-mono">
                                                        {{ $item->employee->nik ?? '' }}</p>
                                                </div>
                                            </div>
                                        </td>

                                        <!-- Gaji Pokok -->
                                        <td class="p-4 font-mono text-slate-600 whitespace-nowrap">
                                            Rp {{ number_format($item->basic_salary, 0, ',', '.') }}
                                        </td>

                                        <!-- Pendapatan Tambahan -->
                                        <td class="p-4 font-mono text-emerald-600 font-semibold whitespace-nowrap">
                                            +Rp
                                            {{ number_format($item->total_allowance + $item->total_bonus, 0, ',', '.') }}
                                        </td>

                                        <!-- Potongan -->
                                        <td class="p-4 font-mono text-rose-500 font-semibold whitespace-nowrap">
                                            -Rp {{ number_format($item->total_deduction, 0, ',', '.') }}
                                        </td>

                                        <!-- Take Home Pay -->
                                        <td
                                            class="p-4 font-extrabold font-mono text-slate-900 bg-emerald-50/30 text-sm whitespace-nowrap">
                                            Rp {{ number_format($item->net_salary, 0, ',', '.') }}
                                        </td>

                                        <!-- Keputusan (Aksi Buttons) -->
                                        <td class="p-4 text-center whitespace-nowrap">
                                            @if ($item->status != 'approved')
                                                <div class="flex items-center justify-center space-x-2">
                                                    <!-- Form ACC / Approve -->
                                                    <form id="form-approve-{{ $item->id }}"
                                                        action="{{ route('payrolls.approve', $item->id) }}"
                                                        method="POST" class="inline">
                                                        @csrf
                                                        @method('PATCH')
                                                        <button type="button"
                                                            onclick="openActionModal('approve', 'form-approve-{{ $item->id }}', '{{ $item->employee->name ?? 'Karyawan' }}')"
                                                            class="px-3 py-1.5 bg-emerald-500 hover:bg-emerald-600 text-white font-bold rounded-xl text-[11px] shadow-md shadow-emerald-500/20 transition flex items-center space-x-1">
                                                            <i class="fa-solid fa-check"></i>
                                                            <span>ACC</span>
                                                        </button>
                                                    </form>

                                                    <!-- Form Tolak / Reject -->
                                                    @if ($item->status != 'rejected')
                                                        <form id="form-reject-{{ $item->id }}"
                                                            action="{{ route('payrolls.reject', $item->id) }}"
                                                            method="POST" class="inline">
                                                            @csrf
                                                            @method('PATCH')
                                                            <button type="button"
                                                                onclick="openActionModal('reject', 'form-reject-{{ $item->id }}', '{{ $item->employee->name ?? 'Karyawan' }}')"
                                                                class="px-3 py-1.5 bg-rose-50 hover:bg-rose-100 text-rose-600 border border-rose-200 font-bold rounded-xl text-[11px] transition flex items-center space-x-1">
                                                                <i class="fa-solid fa-xmark"></i>
                                                                <span>Tolak</span>
                                                            </button>
                                                        </form>
                                                    @endif
                                                </div>
                                            @else
                                                <span
                                                    class="px-2.5 py-1 bg-emerald-50 text-emerald-600 font-extrabold rounded-lg text-[10px] uppercase border border-emerald-200 inline-flex items-center space-x-1">
                                                    <i class="fa-solid fa-lock text-[9px]"></i>
                                                    <span>Approved</span>
                                                </span>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="p-12 text-center text-slate-400">
                                            <div
                                                class="w-16 h-16 mx-auto bg-slate-50 rounded-full flex items-center justify-center mb-3 text-slate-300">
                                                <i class="fa-solid fa-clipboard-check text-2xl"></i>
                                            </div>
                                            <p class="font-bold text-slate-600 text-sm">Tidak Ada Draft Payroll
                                                Menunggu Approval</p>
                                            <p class="text-xs text-slate-400 mt-1">Semua penggajian untuk periode ini
                                                mungkin sudah disetujui atau belum dihitung.</p>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- PAGINATION -->
                    @if (method_exists($payrolls, 'links'))
                        <div class="p-4 border-t border-slate-100 bg-slate-50/50">
                            {{ $payrolls->links() }}
                        </div>
                    @endif
                </div>
            </div>

            <!-- PANEL RINGKASAN OTORISASI (KANAN 1 KOLOM) -->
            <div class="space-y-4">
                <div class="bg-white p-5 rounded-2xl border border-slate-100 shadow-sm space-y-4">
                    <h3 class="text-sm font-extrabold text-slate-800 border-b border-slate-100 pb-3">
                        Ringkasan Otorisasi Payroll
                    </h3>

                    @php
                        $grandTHP = $payrolls->sum('net_salary');
                        $grandBasic = $payrolls->sum('basic_salary');
                        $grandAllowance = $payrolls->sum('total_allowance');
                        $grandBonus = $payrolls->sum('total_bonus');
                        $grandDeduction = $payrolls->sum('total_deduction');
                    @endphp

                    <!-- Total Highlight Card -->
                    <div
                        class="p-4 bg-gradient-to-br from-slate-900 to-slate-800 rounded-2xl text-white space-y-1 shadow-lg shadow-slate-900/10">
                        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Total Anggaran Mencair
                        </p>
                        <h2 class="text-xl font-extrabold font-mono text-emerald-400">
                            Rp {{ number_format($grandTHP, 0, ',', '.') }}
                        </h2>
                        <p class="text-[10px] text-slate-400">*Gaji yang sudah di-ACC otomatis terbit ke Slip Gaji.</p>
                    </div>

                    <!-- Breakdown Ringkas -->
                    <div class="space-y-2 text-xs pt-1">
                        <div class="flex justify-between items-center text-slate-600">
                            <span>Gaji Pokok Total</span>
                            <span class="font-mono font-bold text-slate-800">Rp
                                {{ number_format($grandBasic, 0, ',', '.') }}</span>
                        </div>
                        <div class="flex justify-between items-center text-slate-600">
                            <span>Total Tunjangan & Bonus</span>
                            <span class="font-mono font-bold text-emerald-600">+Rp
                                {{ number_format($grandAllowance + $grandBonus, 0, ',', '.') }}</span>
                        </div>
                        <div class="flex justify-between items-center text-slate-600 border-t border-slate-100 pt-2">
                            <span>Total Potongan Karyawan</span>
                            <span class="font-mono font-bold text-rose-500">-Rp
                                {{ number_format($grandDeduction, 0, ',', '.') }}</span>
                        </div>
                    </div>

                    <!-- Catatan Keamanan Otorisasi -->
                    <div class="p-3 bg-amber-50/60 border border-amber-200/60 rounded-xl space-y-1">
                        <div class="flex items-center space-x-1.5 text-amber-800 font-bold text-[11px]">
                            <i class="fa-solid fa-circle-info text-amber-500"></i>
                            <span>Catatan Otorisasi</span>
                        </div>
                        <p class="text-[10px] text-amber-700 leading-relaxed">
                            Persetujuan (ACC) bersifat final. Setelah disetujui, status akan berubah menjadi
                            **Approved** dan slip gaji karyawan siap diakses pada menu berikutnya.
                        </p>
                    </div>

                    <!-- Shortcut ke Slip Gaji -->
                    <div class="pt-1">
                        <a href="{{ route('payrolls.slip') }}"
                            class="w-full py-3 bg-slate-800 hover:bg-slate-900 text-white font-bold rounded-xl text-xs flex items-center justify-center space-x-2 transition shadow-md">
                            <span>Lihat Slip Gaji Terbit</span>
                            <i class="fa-solid fa-arrow-right text-xs"></i>
                        </a>
                    </div>
                </div>
            </div>

        </div>

    </div>

    <!-- CUSTOM TAILWIND CONFIRMATION MODAL WITH ANIMATION -->
    <div id="confirmationModal"
        class="hidden fixed inset-0 bg-slate-900/40 backdrop-blur-sm z-50 flex items-center justify-center p-4 transition-opacity duration-300 opacity-0">
        <div id="modalBox"
            class="bg-white rounded-3xl max-w-sm w-full p-6 shadow-2xl space-y-5 border border-slate-100 transform transition-all">
            <!-- Icon Container -->
            <div id="modalIconBg"
                class="w-14 h-14 rounded-2xl flex items-center justify-center mx-auto transition-colors">
                <i id="modalIcon" class="text-2xl"></i>
            </div>

            <!-- Text Content -->
            <div class="text-center space-y-1">
                <h3 id="modalTitle" class="font-bold text-slate-800 text-base">Konfirmasi Aksi</h3>
                <p id="modalDescription" class="text-xs text-slate-500 leading-relaxed"></p>
            </div>

            <!-- Action Buttons -->
            <div class="flex items-center space-x-3 pt-2">
                <button type="button" onclick="closeActionModal()"
                    class="w-1/2 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-600 font-bold rounded-xl text-xs transition active:scale-95">
                    Batal
                </button>
                <button type="button" id="btnConfirmSubmit"
                    class="w-1/2 py-2.5 text-white font-bold rounded-xl text-xs shadow-lg transition active:scale-95">
                    Ya, Lanjutkan
                </button>
            </div>
        </div>
    </div>

    <!-- JAVASCRIPT UNTUK MODAL -->
    <script>
        let targetFormId = null;

        function openActionModal(type, formId, name) {
            targetFormId = formId;

            const modal = document.getElementById('confirmationModal');
            const modalBox = document.getElementById('modalBox');
            const iconBg = document.getElementById('modalIconBg');
            const icon = document.getElementById('modalIcon');
            const title = document.getElementById('modalTitle');
            const description = document.getElementById('modalDescription');
            const confirmBtn = document.getElementById('btnConfirmSubmit');

            if (type === 'approve') {
                iconBg.className =
                    "w-14 h-14 bg-emerald-100 text-emerald-600 rounded-2xl flex items-center justify-center mx-auto";
                icon.className = "fa-solid fa-circle-check text-2xl";
                title.innerText = "Setujui Payroll?";
                description.innerHTML =
                    `Apakah kamu yakin ingin menyetujui (ACC) pengajuan penggajian untuk <b>${name}</b>?`;

                confirmBtn.className =
                    "w-1/2 py-2.5 bg-emerald-500 hover:bg-emerald-600 text-white font-bold rounded-xl text-xs shadow-lg shadow-emerald-500/20 transition active:scale-95";
                confirmBtn.innerText = "Ya, Setujui";
            } else {
                iconBg.className =
                    "w-14 h-14 bg-rose-100 text-rose-600 rounded-2xl flex items-center justify-center mx-auto";
                icon.className = "fa-solid fa-triangle-exclamation text-2xl";
                title.innerText = "Tolak Payroll?";
                description.innerHTML = `Apakah kamu yakin ingin menolak draft payroll untuk <b>${name}</b>?`;

                confirmBtn.className =
                    "w-1/2 py-2.5 bg-rose-500 hover:bg-rose-600 text-white font-bold rounded-xl text-xs shadow-lg shadow-rose-500/20 transition active:scale-95";
                confirmBtn.innerText = "Ya, Tolak";
            }

            // Tampilkan modal dengan animasi
            modal.classList.remove('hidden');
            setTimeout(() => {
                modal.classList.remove('opacity-0');
                modalBox.classList.remove('animate-modal-hide');
                modalBox.classList.add('animate-modal-show');
            }, 10);
        }

        function closeActionModal() {
            const modal = document.getElementById('confirmationModal');
            const modalBox = document.getElementById('modalBox');

            modal.classList.add('opacity-0');
            modalBox.classList.remove('animate-modal-show');
            modalBox.classList.add('animate-modal-hide');

            setTimeout(() => {
                modal.classList.add('hidden');
                targetFormId = null;
            }, 200); // Menunggu animasi selesai sebelum disembunyikan
        }

        document.getElementById('btnConfirmSubmit').addEventListener('click', function() {
            if (targetFormId) {
                document.getElementById(targetFormId).submit();
            }
        });
    </script>
</x-app-layout>
