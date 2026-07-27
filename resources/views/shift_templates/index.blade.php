@extends('layouts.app')

@section('content')
    <div class="space-y-6">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-xl font-extrabold text-slate-800">Master Jam Kerja Shift</h1>
                <p class="text-xs text-slate-400 mt-1">Atur jam masuk dan keluar untuk setiap shift operasional.</p>
            </div>
            <div class="flex items-center gap-2">
                <a href="{{ route('employee-shifts.index') }}"
                    class="px-3.5 py-2.5 bg-slate-100 text-slate-600 font-bold rounded-xl text-xs hover:bg-slate-200 transition">
                    <i class="fa-solid fa-arrow-left mr-1"></i> Kembali
                </a>
                <button onclick="openModalCreate()"
                    class="bg-[#FF6B00] hover:bg-orange-600 text-white px-4 py-2.5 rounded-xl font-extrabold text-xs shadow-md transition flex items-center gap-2">
                    <i class="fa-solid fa-plus"></i>
                    <span>Tambah Shift Baru</span>
                </button>
            </div>
        </div>

        @if (session('success'))
            <div class="bg-emerald-50 border border-emerald-200 text-emerald-700 px-4 py-3 rounded-2xl text-xs font-bold">
                <i class="fa-solid fa-circle-check mr-1"></i> {{ session('success') }}
            </div>
        @endif

        <div class="bg-white rounded-3xl p-6 border border-slate-100 shadow-sm">
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs">
                    <thead>
                        <tr class="text-slate-400 border-b border-slate-100 uppercase text-[10px] tracking-wider">
                            <th class="pb-3">Nama Shift</th>
                            <th class="pb-3">Jam Masuk</th>
                            <th class="pb-3">Jam Keluar</th>
                            <th class="pb-3">Durasi</th>
                            <th class="pb-3 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 font-medium">
                        @forelse($shifts as $shift)
                            <tr>
                                <td class="py-4 font-extrabold text-slate-800">{{ $shift->name }}</td>
                                <td class="py-4 text-emerald-600 font-bold">
                                    {{ \Carbon\Carbon::parse($shift->start_time)->format('H:i') }} WIB</td>
                                <td class="py-4 text-rose-600 font-bold">
                                    {{ \Carbon\Carbon::parse($shift->end_time)->format('H:i') }} WIB</td>
                                <td class="py-4 text-slate-600 font-bold">{{ $shift->duration_hours }} Jam</td>
                                <td class="py-4 text-right space-x-1">
                                    <button
                                        onclick="openModalEdit('{{ $shift->id }}', '{{ $shift->name }}', '{{ $shift->start_time }}', '{{ $shift->end_time }}')"
                                        class="p-2 text-slate-400 hover:text-[#FF6B00]">
                                        <i class="fa-solid fa-pen-to-square"></i>
                                    </button>
                                    <form action="{{ route('shift-templates.destroy', $shift->id) }}" method="POST"
                                        class="inline" onsubmit="return confirm('Hapus shift ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="p-2 text-slate-400 hover:text-rose-500">
                                            <i class="fa-solid fa-trash"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="py-8 text-center text-slate-400">Belum ada master shift. Silakan
                                    buat baru.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- MODAL -->
    <div id="shift-modal"
        class="fixed inset-0 z-50 hidden bg-slate-900/60 backdrop-blur-sm flex items-center justify-center p-4">
        <div class="bg-white rounded-3xl max-w-md w-full p-6 space-y-4 shadow-2xl">
            <h3 id="modal-title" class="font-black text-sm text-slate-800">Tambah Master Shift</h3>
            <form id="shift-form" method="POST" class="space-y-4 text-xs font-semibold">
                @csrf
                <input type="hidden" name="_method" id="form-method" value="POST">

                <div>
                    <label class="block text-slate-600 mb-1">Nama Shift</label>
                    <input type="text" name="name" id="shift-name" required placeholder="Contoh: Shift Pagi"
                        class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2.5 focus:outline-none focus:border-[#FF6B00]">
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-slate-600 mb-1">Jam Masuk</label>
                        <input type="time" name="start_time" id="shift-start" required
                            class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2.5 focus:outline-none focus:border-[#FF6B00]">
                    </div>
                    <div>
                        <label class="block text-slate-600 mb-1">Jam Keluar</label>
                        <input type="time" name="end_time" id="shift-end" required
                            class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2.5 focus:outline-none focus:border-[#FF6B00]">
                    </div>
                </div>

                <div class="flex justify-end gap-2 pt-2">
                    <button type="button" onclick="closeModal()"
                        class="px-4 py-2.5 bg-slate-100 text-slate-600 font-bold rounded-xl">Batal</button>
                    <button type="submit" class="px-5 py-2.5 bg-[#FF6B00] text-white font-extrabold rounded-xl">Simpan Jam
                        Shift</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function openModalCreate() {
            document.getElementById('modal-title').innerText = "Tambah Master Shift";
            document.getElementById('shift-form').action = "{{ route('shift-templates.store') }}";
            document.getElementById('form-method').value = "POST";
            document.getElementById('shift-name').value = "";
            document.getElementById('shift-start').value = "07:00";
            document.getElementById('shift-end').value = "15:00";
            document.getElementById('shift-modal').classList.remove('hidden');
        }

        function openModalEdit(id, name, start, end) {
            document.getElementById('modal-title').innerText = "Edit Jam Kerja Shift";
            document.getElementById('shift-form').action = "/shift-templates/" + id;
            document.getElementById('form-method').value = "PUT";
            document.getElementById('shift-name').value = name;
            document.getElementById('shift-start').value = start.substring(0, 5);
            document.getElementById('shift-end').value = end.substring(0, 5);
            document.getElementById('shift-modal').classList.remove('hidden');
        }

        function closeModal() {
            document.getElementById('shift-modal').classList.add('hidden');
        }
    </script>
@endsection
