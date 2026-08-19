<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Slip Gaji - {{ $payroll->employee->name ?? 'Karyawan' }}
        ({{ ($payroll->payroll_type ?? ($payroll->employee->employee_type ?? '')) == 'Harian' ? $payroll->payroll_date : $payroll->month_year }})
    </title>
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

    <div class="no-print w-full max-w-2xl flex justify-between items-center mb-4">
        <a href="javascript:window.close();" class="text-xs font-bold text-slate-600 hover:text-slate-800">
            &larr; Tutup Halaman
        </a>
        <button onclick="window.print()"
            class="px-4 py-2 bg-[#FF6B00] text-white font-bold rounded-xl text-xs shadow-md hover:bg-[#e66000] transition">
            🖨️ Cetak / Download PDF
        </button>
    </div>

    <div class="bg-white w-full max-w-2xl p-8 rounded-2xl border border-slate-200 shadow-xl">
        <div class="flex items-center justify-between pb-6 border-b border-slate-200">
            <div>
                <h1 class="text-xl font-extrabold text-slate-800 tracking-tight">GB PARKING SYSTEM</h1>
                <p class="text-xs text-slate-500">Slip Gaji Resmi Karyawan ({{ $payroll->payroll_type ?? 'Bulanan' }})
                </p>
            </div>
            <div class="text-right">
                <span class="px-3 py-1 bg-orange-100 text-[#FF6B00] text-xs font-bold rounded-lg uppercase">
                    {{ $payroll->status }}
                </span>
                <p class="text-[11px] text-slate-400 mt-1">
                    Periode:
                    {{ ($payroll->payroll_type ?? '') == 'Harian' ? \Carbon\Carbon::parse($payroll->payroll_date)->translatedFormat('d M Y') : \Carbon\Carbon::parse($payroll->month_year)->translatedFormat('F Y') }}
                </p>
            </div>
        </div>

        <div
            class="grid grid-cols-2 gap-4 my-6 text-xs text-slate-700 bg-slate-50 p-4 rounded-xl border border-slate-100">
            <div>
                <p class="text-slate-400 font-medium">Nama Karyawan:</p>
                <p class="font-bold text-slate-800 text-sm">{{ $payroll->employee->name ?? '-' }}</p>
            </div>
            <div>
                <p class="text-slate-400 font-medium">NIK:</p>
                <p class="font-bold text-slate-800">{{ $payroll->employee->nik ?? '-' }}</p>
            </div>
            <div>
                <p class="text-slate-400 font-medium">Jabatan / Tipe:</p>
                <p class="font-semibold">{{ $payroll->employee->position->name ?? '-' }}
                    ({{ $payroll->payroll_type ?? ($payroll->employee->employee_type ?? 'Tetap') }})</p>
            </div>
            <div>
                <p class="text-slate-400 font-medium">Lokasi Kerja:</p>
                <p class="font-semibold">{{ $payroll->employee->location->name ?? '-' }}</p>
            </div>
        </div>

        <div class="space-y-4 text-xs">
            <!-- A. PENDAPATAN -->
            <div>
                <h3 class="font-bold text-slate-800 uppercase tracking-wider mb-2 text-[11px] text-[#FF6B00]">A.
                    PENDAPATAN</h3>
                <div class="space-y-1.5 pl-2">
                    <div class="flex justify-between text-slate-600">
                        <span>Gaji Pokok / Rate Harian</span>
                        <span class="font-semibold font-mono">Rp
                            {{ number_format($payroll->basic_salary, 0, ',', '.') }}</span>
                    </div>

                    @if ($payroll->total_allowance > 0)
                        <div class="flex justify-between text-slate-600">
                            <span>Tunjangan</span>
                            <span class="font-semibold text-blue-600 font-mono">+ Rp
                                {{ number_format($payroll->total_allowance, 0, ',', '.') }}</span>
                        </div>
                    @endif

                    @if ($payroll->total_bonus > 0)
                        <div class="flex justify-between text-slate-600">
                            <span>Bonus / Insentif</span>
                            <span class="font-semibold text-emerald-600 font-mono">+ Rp
                                {{ number_format($payroll->total_bonus, 0, ',', '.') }}</span>
                        </div>
                    @endif
                </div>
            </div>

            <!-- B. LOGIKA POTONGAN DINAMIS -->
            @php
                $pType = $payroll->payroll_type ?? ($payroll->employee->employee_type ?? 'Bulanan');

                // Jika karyawan Bulanan, hitung BPJS & Pajak
                if ($pType === 'Bulanan') {
                    $bpjsDeduction = $payroll->basic_salary * 0.03;
                    $taxDeduction = $payroll->basic_salary * 0.05;
                } else {
                    $bpjsDeduction = 0;
                    $taxDeduction = 0;
                }

                // Sisa potongan dari total_deduction jika ada potongan komponen lain
                $otherDeduction = max(0, $payroll->total_deduction - ($bpjsDeduction + $taxDeduction));
            @endphp

            @if ($payroll->total_deduction > 0 || $pType === 'Bulanan')
                <div class="pt-2 border-t border-slate-100">
                    <h3 class="font-bold text-slate-800 uppercase tracking-wider mb-2 text-[11px] text-rose-600">B.
                        POTONGAN</h3>
                    <div class="space-y-1.5 pl-2">
                        <!-- Potongan BPJS -->
                        <div class="flex justify-between text-slate-600">
                            <span>Potongan BPJS (3%)</span>
                            <span class="font-semibold text-rose-500 font-mono">- Rp
                                {{ number_format($bpjsDeduction, 0, ',', '.') }}</span>
                        </div>

                        <!-- Potongan PPh 21 -->
                        <div class="flex justify-between text-slate-600">
                            <span>Pajak PPh 21 (5%)</span>
                            <span class="font-semibold text-rose-500 font-mono">- Rp
                                {{ number_format($taxDeduction, 0, ',', '.') }}</span>
                        </div>

                        <!-- Potongan Komponen Lainnya Jika Ada -->
                        @if ($otherDeduction > 0)
                            <div class="flex justify-between text-slate-600">
                                <span>Potongan Komponen / Lainnya</span>
                                <span class="font-semibold text-rose-500 font-mono">- Rp
                                    {{ number_format($otherDeduction, 0, ',', '.') }}</span>
                            </div>
                        @endif
                    </div>
                </div>
            @endif

            <!-- TAKE HOME PAY -->
            <div class="p-4 bg-slate-900 text-white rounded-xl flex items-center justify-between mt-6">
                <div>
                    <p class="text-xs font-bold uppercase tracking-wider text-orange-400">TAKE HOME PAY</p>
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
            <div class="mt-4 p-3 bg-amber-50 border border-amber-200 rounded-xl text-xs text-amber-800">
                <strong>Catatan:</strong> {{ $payroll->notes }}
            </div>
        @endif

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

</body>

</html>
