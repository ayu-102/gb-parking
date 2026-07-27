<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Slip Gaji - {{ $payroll->employee->name ?? 'Karyawan' }} ({{ $payroll->month_year }})</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @media print {
            .no-print {
                display: none !important;
            }

            body {
                background: white !important;
                padding: 0 !important;
            }
        }
    </style>
</head>

<body class="bg-slate-100 font-sans p-6 min-h-screen flex flex-col items-center justify-center">

    <!-- Tombol Cetak / Kembali (Hanya tampil di layar) -->
    <div class="no-print w-full max-w-2xl flex justify-between items-center mb-4">
        <a href="javascript:window.close();" class="text-xs font-bold text-slate-600 hover:text-slate-800">
            &larr; Tutup Halaman
        </a>
        <button onclick="window.print()"
            class="px-4 py-2 bg-[#FF6B00] text-white font-bold rounded-xl text-xs shadow-md hover:bg-[#e66000] transition">
            🖨️ Cetak / Download PDF
        </button>
    </div>

    <!-- Container Slip Gaji Resmi -->
    <div class="bg-white w-full max-w-2xl border border-slate-200 p-8 rounded-2xl shadow-sm text-slate-800">

        <!-- Header Perusahaan -->
        <div class="flex justify-between items-start border-b border-slate-200 pb-4 mb-6">
            <div>
                <h1 class="text-xl font-black tracking-wider text-slate-900 uppercase">GB PARKING</h1>
                <p class="text-[11px] text-slate-500">Sistem Penggajian & Manajemen Karyawan</p>
            </div>
            <div class="text-right">
                <span
                    class="px-3 py-1 bg-emerald-100 text-emerald-800 font-bold text-[10px] rounded-full uppercase">SLIP
                    GAJI RESMI</span>
                <p class="text-xs font-semibold text-slate-600 mt-1">Periode: {{ $payroll->month_year }}</p>
            </div>
        </div>

        <!-- Identitas Karyawan -->
        <div class="grid grid-cols-2 gap-4 bg-slate-50 p-4 rounded-xl text-xs mb-6">
            <div>
                <p class="text-slate-400">Nama Karyawan:</p>
                <p class="font-bold text-slate-800 text-sm">{{ $payroll->employee->name ?? '-' }}</p>
            </div>
            <div>
                <p class="text-slate-400">NIK / ID Karyawan:</p>
                <p class="font-bold text-slate-800 text-sm">{{ $payroll->employee->nik ?? '-' }}</p>
            </div>
        </div>

        <div class="space-y-4">
            <!-- RINCIAN PENERIMAAN & POTONGAN -->
            <div class="border-b border-slate-200 pb-2">
                <h3 class="text-xs font-bold text-slate-500 uppercase tracking-wider">Rincian Penerimaan & Potongan</h3>
            </div>

            <!-- TABEL 2 KOLOM (PENDAPATAN VS POTONGAN) -->
            <div class="grid grid-cols-2 gap-6 text-xs">

                <!-- KOLOM PENDAPATAN -->
                <div class="space-y-2">
                    <p class="font-bold text-slate-700 border-b pb-1 text-[11px] uppercase tracking-wide">Penerimaan
                        (Income)</p>
                    <div class="flex justify-between py-1 border-b border-slate-100">
                        <span class="text-slate-600">Gaji Pokok</span>
                        <span class="font-mono font-bold text-slate-800">Rp
                            {{ number_format($payroll->basic_salary, 0, ',', '.') }}</span>
                    </div>
                    <div class="flex justify-between py-1 border-b border-slate-100">
                        <span class="text-slate-600">Total Tunjangan</span>
                        <span class="font-mono font-semibold text-blue-600">+ Rp
                            {{ number_format($payroll->total_allowance, 0, ',', '.') }}</span>
                    </div>
                    <div class="flex justify-between py-1 border-b border-slate-100">
                        <span class="text-slate-600">Total Bonus & Insentif</span>
                        <span class="font-mono font-semibold text-emerald-600">+ Rp
                            {{ number_format($payroll->total_bonus, 0, ',', '.') }}</span>
                    </div>
                </div>

                <!-- KOLOM POTONGAN (RINCIAN DIBREAKDOWN) -->
                <div class="space-y-2">
                    <p class="font-bold text-slate-700 border-b pb-1 text-[11px] uppercase tracking-wide">Potongan
                        (Deduction)</p>

                    @php
                        $estBpjs = $payroll->basic_salary * 0.03; // BPJS 3%
                        $estTax = $payroll->basic_salary * 0.05; // Pajak 5%
                        $estOther = max(0, $payroll->total_deduction - ($estBpjs + $estTax)); // Kasbon
                    @endphp

                    <div class="flex justify-between py-1 border-b border-slate-100">
                        <span class="text-slate-600">BPJS Kesehatan & Ketenagakerjaan (3%)</span>
                        <span class="font-mono text-rose-500">- Rp {{ number_format($estBpjs, 0, ',', '.') }}</span>
                    </div>
                    <div class="flex justify-between py-1 border-b border-slate-100">
                        <span class="text-slate-600">Pajak PPh 21 (5%)</span>
                        <span class="font-mono text-rose-500">- Rp {{ number_format($estTax, 0, ',', '.') }}</span>
                    </div>
                    @if ($estOther > 0)
                        <div class="flex justify-between py-1 border-b border-slate-100">
                            <span class="text-slate-600">Kasbon / Potongan Lain</span>
                            <span class="font-mono text-rose-500">- Rp
                                {{ number_format($estOther, 0, ',', '.') }}</span>
                        </div>
                    @endif
                    <div class="flex justify-between py-1 font-bold text-rose-600 bg-rose-50/50 px-2 rounded">
                        <span>Total Potongan</span>
                        <span class="font-mono">- Rp {{ number_format($payroll->total_deduction, 0, ',', '.') }}</span>
                    </div>
                </div>

            </div>

            <!-- TAKE HOME PAY -->
            <div class="p-4 bg-slate-900 rounded-xl text-white flex justify-between items-center mt-4">
                <div>
                    <p class="text-[10px] text-slate-400 font-bold uppercase tracking-wider">Gaji Bersih (Take Home Pay)
                    </p>
                    <p class="text-[10px] text-slate-300">Total Akhir Diterima Karyawan</p>
                </div>
                <div class="text-right">
                    <span class="text-lg font-extrabold font-mono text-emerald-400">
                        Rp {{ number_format($payroll->net_salary, 0, ',', '.') }}
                    </span>
                </div>
            </div>
        </div>

        @if ($payroll->notes)
            <div class="mb-6 p-3 bg-amber-50 border border-amber-200 rounded-xl text-xs text-amber-800">
                <strong>Catatan:</strong> {{ $payroll->notes }}
            </div>
        @endif

        <!-- Tanda Tangan -->
        <div class="grid grid-cols-2 gap-8 text-center text-xs mt-12 pt-4 border-t border-slate-100">
            <div>
                <p class="text-slate-400 mb-12">Penerima,</p>
                <p class="font-bold underline">{{ $payroll->employee->name ?? 'Karyawan' }}</p>
            </div>
            <div>
                <p class="text-slate-400 mb-12">HRD / Finance Manager,</p>
                <p class="font-bold underline">Super Admin</p>
            </div>
        </div>

    </div>

    <!-- Auto Print Script -->
    <script>
        // Otomatis memicu pop-up cetak saat halaman dibuka
        window.onload = function() {
            // Un-comment jika ingin langsung otomatis membuka window print saat tab terbuka:
            // window.print();
        }
    </script>
</body>

</html>
