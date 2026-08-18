<x-app-layout>
    <div class="max-w-3xl mx-auto space-y-6">
        <!-- HEADER -->
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-slate-800 tracking-tight">Edit Data Karyawan</h1>
                <p class="text-xs text-slate-500 mt-1">Perbarui informasi staf atau akun login petugas lapangan GB
                    Parking.</p>
            </div>
            <a href="{{ route('employees.index') }}"
                class="px-3.5 py-2 border border-slate-200 text-slate-600 hover:bg-slate-50 font-bold rounded-xl text-xs transition">
                <i class="fa-solid fa-arrow-left mr-1"></i> Kembali
            </a>
        </div>

        <!-- FORM CARD -->
        <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-6">
            <form action="{{ route('employees.update', $employee->id) }}" method="POST" class="space-y-5">
                @csrf
                @method('PUT')

                <!-- INFORMASI KREDENSIAL LOGIN -->
                <div class="p-4 bg-slate-50 rounded-2xl border border-slate-200/80 space-y-3">
                    <div class="flex items-center justify-between">
                        <h3 class="text-xs font-bold text-slate-700 uppercase tracking-wider flex items-center gap-1.5">
                            <i class="fa-solid fa-user-lock text-[#FF6B00]"></i> Akun Akses Login
                        </h3>
                        @if ($employee->user)
                            <span class="text-[10px] font-bold px-2 py-0.5 rounded-md bg-emerald-100 text-emerald-700">
                                <i class="fa-solid fa-circle-check mr-1"></i> Terhubung Akun
                            </span>
                        @else
                            <span class="text-[10px] font-bold px-2 py-0.5 rounded-md bg-amber-100 text-amber-700">
                                <i class="fa-solid fa-triangle-exclamation mr-1"></i> Tanpa Akun
                            </span>
                        @endif
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-600 uppercase tracking-wider mb-1">
                            Email Login <span class="text-rose-500">*</span>
                        </label>
                        <input type="email" name="email"
                            value="{{ old('email', $employee->email ?? ($employee->user->email ?? '')) }}" required
                            placeholder="karyawan@gbparking.com"
                            class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs focus:outline-none focus:border-[#FF6B00] bg-white @error('email') border-red-500 @enderror">
                        @error('email')
                            <p class="text-red-500 text-[10px] mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- BIODATA KARYAWAN -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-600 uppercase tracking-wider mb-1">NIK (Nomor
                            Induk Karyawan) <span class="text-rose-500">*</span></label>
                        <input type="text" name="nik" value="{{ old('nik', $employee->nik) }}" required
                            class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs focus:outline-none focus:border-[#FF6B00] @error('nik') border-red-500 @enderror">
                        @error('nik')
                            <p class="text-red-500 text-[10px] mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-600 uppercase tracking-wider mb-1">Nama Lengkap
                            <span class="text-rose-500">*</span></label>
                        <input type="text" name="name" value="{{ old('name', $employee->name) }}" required
                            class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs focus:outline-none focus:border-[#FF6B00] @error('name') border-red-500 @enderror">
                        @error('name')
                            <p class="text-red-500 text-[10px] mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-600 uppercase tracking-wider mb-1">No. Whatsapp
                            / HP</label>
                        <input type="text" name="phone" value="{{ old('phone', $employee->phone) }}"
                            class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs focus:outline-none focus:border-[#FF6B00]">
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-600 uppercase tracking-wider mb-1">Status
                            Karyawan</label>
                        <select name="status"
                            class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs focus:outline-none focus:border-[#FF6B00]">
                            <option value="Aktif" {{ old('status', $employee->status) == 'Aktif' ? 'selected' : '' }}>
                                Aktif</option>
                            <option value="Nonaktif"
                                {{ old('status', $employee->status) == 'Nonaktif' ? 'selected' : '' }}>Nonaktif
                            </option>
                        </select>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <label
                            class="block text-xs font-bold text-slate-600 uppercase tracking-wider mb-1">Departemen</label>
                        <select name="department_id"
                            class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs focus:outline-none focus:border-[#FF6B00]">
                            <option value="">-- Pilih Departemen --</option>
                            @foreach ($departments as $department)
                                <option value="{{ $department->id }}"
                                    {{ old('department_id', $employee->department_id) == $department->id ? 'selected' : '' }}>
                                    {{ $department->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-600 uppercase tracking-wider mb-1">Lokasi
                            Penempatan</label>
                        <select name="location_id"
                            class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs focus:outline-none focus:border-[#FF6B00]">
                            <option value="">-- Pilih Lokasi --</option>
                            @foreach ($locations as $location)
                                <option value="{{ $location->id }}"
                                    {{ old('location_id', $employee->location_id) == $location->id ? 'selected' : '' }}>
                                    {{ $location->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-600 uppercase tracking-wider mb-1">Jabatan
                            <span class="text-rose-500">*</span></label>
                        <select name="position_id" required
                            class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs focus:outline-none focus:border-[#FF6B00] @error('position_id') border-red-500 @enderror">
                            <option value="">-- Pilih Jabatan --</option>
                            @foreach ($positions as $position)
                                <option value="{{ $position->id }}"
                                    {{ old('position_id', $employee->position_id) == $position->id ? 'selected' : '' }}>
                                    {{ $position->name ?? ($position->title ?? $position->nama_jabatan) }}
                                </option>
                            @endforeach
                        </select>
                        @error('position_id')
                            <p class="text-red-500 text-[10px] mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-600 uppercase tracking-wider mb-1">Gaji Pokok /
                            Rate Harian (Rp) <span class="text-rose-500">*</span></label>
                        <input type="number" name="basic_salary"
                            value="{{ old('basic_salary', $employee->basic_salary) }}" required
                            class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs focus:outline-none focus:border-[#FF6B00]">
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-600 uppercase tracking-wider mb-1">Tipe
                            Karyawan <span class="text-rose-500">*</span></label>
                        <select name="employee_type" id="employee_type" required
                            class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs focus:outline-none focus:border-[#FF6B00] @error('employee_type') border-red-500 @enderror">
                            <option value="Kontrak"
                                {{ old('employee_type', $employee->employee_type) == 'Kontrak' ? 'selected' : '' }}>
                                Kontrak</option>
                            <option value="Tetap"
                                {{ old('employee_type', $employee->employee_type) == 'Tetap' ? 'selected' : '' }}>Tetap
                            </option>
                            <option value="Harian"
                                {{ old('employee_type', $employee->employee_type) == 'Harian' ? 'selected' : '' }}>
                                Harian</option>
                        </select>
                        @error('employee_type')
                            <p class="text-red-500 text-[10px] mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- TANGGAL BERAKHIR KONTRAK (DYNAMIC SHOW/HIDE) -->
                <div id="contract_date_container"
                    class="{{ old('employee_type', $employee->employee_type) == 'Kontrak' ? '' : 'hidden' }}">
                    <label class="block text-xs font-bold text-slate-600 uppercase tracking-wider mb-1">Tanggal Berakhir
                        Kontrak <span class="text-rose-500">*</span></label>
                    <input type="date" name="contract_end_date"
                        value="{{ old('contract_end_date', $employee->contract_end_date ? \Carbon\Carbon::parse($employee->contract_end_date)->format('Y-m-d') : '') }}"
                        class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs focus:outline-none focus:border-[#FF6B00] @error('contract_end_date') border-red-500 @enderror">
                    @error('contract_end_date')
                        <p class="text-red-500 text-[10px] mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- SUBMIT BUTTON -->
                <div class="pt-4 flex justify-end space-x-3 border-t border-slate-100 mt-6">
                    <a href="{{ route('employees.index') }}"
                        class="px-5 py-2.5 border border-slate-200 text-slate-600 font-bold text-xs rounded-xl hover:bg-slate-50 transition">
                        Batal
                    </a>
                    <button type="submit"
                        class="px-5 py-2.5 bg-[#FF6B00] hover:bg-[#e66000] text-white font-bold text-xs rounded-xl shadow-lg shadow-orange-500/20 transition">
                        Perbarui Data Karyawan
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const employeeTypeSelect = document.getElementById('employee_type');
            const contractDateContainer = document.getElementById('contract_date_container');

            function toggleContractDate() {
                if (employeeTypeSelect.value === 'Kontrak') {
                    contractDateContainer.classList.remove('hidden');
                } else {
                    contractDateContainer.classList.add('hidden');
                }
            }

            employeeTypeSelect.addEventListener('change', toggleContractDate);
            toggleContractDate();
        });
    </script>
</x-app-layout>
