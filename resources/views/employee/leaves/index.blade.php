@extends('layouts.karyawan')

@section('content')
    <div class="space-y-6">
        <!-- FLASH MESSAGE -->
        @if (session('success'))
            <div class="bg-emerald-50 border border-emerald-200 text-emerald-700 px-4 py-3 rounded-2xl text-xs font-bold">
                <i class="fa-solid fa-circle-check mr-1"></i> {{ session('success') }}
            </div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

            <!-- FORM PENGAJUAN -->
            <div class="bg-white rounded-3xl p-6 border border-slate-100 shadow-sm space-y-4">
                <div class="border-b border-slate-100 pb-3">
                    <h2 class="font-extrabold text-sm text-slate-800">Form Pengajuan Cuti / Izin</h2>
                    <p class="text-[11px] text-slate-400">Isi formulir di bawah untuk mengajukan perizinan.</p>
                </div>

                <form action="{{ route('employee.leaves.store') }}" method="POST" enctype="multipart/form-data"
                    class="space-y-4 text-xs font-semibold">
                    @csrf
                    <div>
                        <label class="block text-slate-600 mb-1">Tipe Perizinan</label>
                        <select name="type" required
                            class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2.5 focus:outline-none focus:border-[#FF6B00]">
                            <option value="Cuti">Cuti Tahunan</option>
                            <option value="Izin">Izin Keperluan</option>
                            <option value="Sakit">Sakit (Lampirkan Surat)</option>
                        </select>
                    </div>

                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block text-slate-600 mb-1">Dari Tanggal</label>
                            <input type="date" name="start_date" required
                                class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2.5 focus:outline-none focus:border-[#FF6B00]">
                        </div>
                        <div>
                            <label class="block text-slate-600 mb-1">Sampai Tanggal</label>
                            <input type="date" name="end_date" required
                                class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2.5 focus:outline-none focus:border-[#FF6B00]">
                        </div>
                    </div>

                    <div>
                        <label class="block text-slate-600 mb-1">Alasan / Keterangan</label>
                        <textarea name="reason" rows="3" required placeholder="Jelaskan alasan pengajuan..."
                            class="w-full bg-slate-50 border border-slate-200 rounded-xl p-3 focus:outline-none focus:border-[#FF6B00]"></textarea>
                    </div>

                    <div>
                        <label class="block text-slate-600 mb-1">Lampiran Foto / Surat Dokter (Opsional)</label>
                        <input type="file" name="attachment" accept="image/*"
                            class="w-full bg-slate-50 border border-slate-200 rounded-xl p-2 text-slate-500 text-[11px]">
                    </div>

                    <button type="submit"
                        class="w-full py-3 bg-[#FF6B00] hover:bg-orange-600 text-white font-extrabold rounded-xl shadow-lg shadow-orange-500/20 transition">
                        Kirim Pengajuan
                    </button>
                </form>
            </div>

            <!-- RIWAYAT PENGAJUAN -->
            <div class="lg:col-span-2 bg-white rounded-3xl p-6 border border-slate-100 shadow-sm space-y-4">
                <h2 class="font-extrabold text-sm text-slate-800 border-b border-slate-100 pb-3">Riwayat Pengajuan Saya</h2>

                <div class="overflow-x-auto">
                    <table class="w-full text-left text-xs">
                        <thead>
                            <tr class="text-slate-400 border-b border-slate-100 uppercase text-[10px] tracking-wider">
                                <th class="pb-3">Tipe</th>
                                <th class="pb-3">Tanggal</th>
                                <th class="pb-3">Durasi</th>
                                <th class="pb-3">Status</th>
                                <th class="pb-3">Catatan Admin</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @forelse($leaves as $leave)
                                <tr>
                                    <td class="py-3 font-extrabold text-slate-800">{{ $leave->type }}</td>
                                    <td class="py-3 text-slate-600">
                                        {{ \Carbon\Carbon::parse($leave->start_date)->format('d M Y') }}
                                        @if ($leave->start_date != $leave->end_date)
                                            - {{ \Carbon\Carbon::parse($leave->end_date)->format('d M Y') }}
                                        @endif
                                    </td>
                                    <td class="py-3 font-bold text-slate-700">{{ $leave->total_days }} Hari</td>
                                    <td class="py-3">
                                        @if ($leave->status == 'pending')
                                            <span
                                                class="bg-amber-50 text-amber-600 px-2.5 py-1 rounded-full font-bold text-[10px]">Menunggu
                                                ACC</span>
                                        @elseif($leave->status == 'approved')
                                            <span
                                                class="bg-emerald-50 text-emerald-600 px-2.5 py-1 rounded-full font-bold text-[10px]">Disetujui</span>
                                        @else
                                            <span
                                                class="bg-rose-50 text-rose-600 px-2.5 py-1 rounded-full font-bold text-[10px]">Ditolak</span>
                                        @endif
                                    </td>
                                    <td class="py-3 text-slate-400 italic">{{ $leave->rejection_note ?? '-' }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="py-8 text-center text-slate-400 font-medium">Belum ada riwayat
                                        pengajuan.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>
@endsection
