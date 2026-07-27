@extends('layouts.karyawan') <!-- Sesuaikan dengan nama master layout karyawan kamu -->

@section('content')
    <div class="space-y-6">

        <!-- HEADER PAGE -->
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div>
                <h1 class="text-2xl font-bold text-slate-800 tracking-tight">Slip Gaji Saya</h1>
                <p class="text-xs text-slate-500 mt-1">Riwayat penerimaan gaji bulanan dan rincian dokumen resmi Anda.</p>
            </div>
        </div>

        <!-- MAIN TABLE CONTAINER -->
        <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
            <div class="p-4 border-b border-slate-100 bg-slate-50/50 flex justify-between items-center">
                <div class="text-xs font-bold text-slate-700">
                    <i class="fa-solid fa-receipt text-[#FF6B00] mr-2"></i>Daftar Slip Terbit
                </div>
                <div class="text-[11px] text-slate-400">
                    <i class="fa-solid fa-circle-check text-emerald-500 mr-1"></i>Hanya menampilkan gaji yang disetujui
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs">
                    <thead class="bg-slate-50 text-slate-400 font-bold border-b border-slate-100 uppercase tracking-wider">
                        <tr>
                            <th class="p-4">Periode</th>
                            <th class="p-4">Gaji Pokok</th>
                            <th class="p-4">Tunjangan</th>
                            <th class="p-4">Bonus / Insentif</th>
                            <th class="p-4">Potongan</th>
                            <th class="p-4">Take Home Pay</th>
                            <th class="p-4 text-center">Aksi Dokumen</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 text-slate-700">
                        @forelse ($payrolls as $item)
                            <tr class="hover:bg-slate-50/80 transition">
                                <td class="p-4 font-bold text-slate-800 whitespace-nowrap">
                                    {{ \Carbon\Carbon::parse($item->month_year)->translatedFormat('F Y') }}
                                </td>
                                <td class="p-4 font-mono text-slate-600 whitespace-nowrap">
                                    Rp {{ number_format($item->basic_salary, 0, ',', '.') }}
                                </td>
                                <td class="p-4 font-mono text-blue-600 font-semibold whitespace-nowrap">
                                    +Rp {{ number_format($item->total_allowance, 0, ',', '.') }}
                                </td>
                                <td class="p-4 font-mono text-emerald-600 font-semibold whitespace-nowrap">
                                    +Rp {{ number_format($item->total_bonus, 0, ',', '.') }}
                                </td>
                                <td class="p-4 font-mono text-rose-500 font-semibold whitespace-nowrap">
                                    -Rp {{ number_format($item->total_deduction, 0, ',', '.') }}
                                </td>
                                <td
                                    class="p-4 font-extrabold font-mono text-emerald-600 bg-emerald-50/30 text-sm whitespace-nowrap">
                                    Rp {{ number_format($item->net_salary, 0, ',', '.') }}
                                </td>
                                <td class="p-4 text-center whitespace-nowrap">
                                    <a href="{{ route('payrolls.print', $item->id) }}" target="_blank"
                                        class="px-3.5 py-2 bg-[#FF6B00] hover:bg-orange-600 text-white font-bold rounded-xl text-[11px] shadow-sm transition inline-flex items-center space-x-1.5 shadow-orange-500/20">
                                        <i class="fa-solid fa-print text-xs"></i>
                                        <span>Cetak Slip</span>
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="p-12 text-center text-slate-400">
                                    <div
                                        class="w-16 h-16 mx-auto bg-slate-50 rounded-full flex items-center justify-center mb-3 text-slate-300">
                                        <i class="fa-solid fa-receipt text-2xl"></i>
                                    </div>
                                    <p class="font-bold text-slate-600 text-sm">Belum Ada Slip Gaji</p>
                                    <p class="text-xs text-slate-400 mt-1">Laporan slip gaji bulanan Anda akan muncul di
                                        sini setelah disetujui oleh HR/Admin.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if (method_exists($payrolls, 'links'))
                <div class="p-4 border-t border-slate-100 bg-slate-50/50">
                    {{ $payrolls->links() }}
                </div>
            @endif
        </div>
    </div>
@endsection
