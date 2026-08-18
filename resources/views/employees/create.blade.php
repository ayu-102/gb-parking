<x-app-layout>
    <div class="max-w-3xl mx-auto space-y-6">
        <!-- HEADER -->
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-slate-800 tracking-tight">Tambah Karyawan Baru</h1>
                <p class="text-xs text-slate-500 mt-1">Isi formulir data staf dan buatkan akun login aplikasi.</p>
            </div>
            <a href="{{ route('employees.index') }}"
                class="px-3.5 py-2 border border-slate-200 text-slate-600 hover:bg-slate-50 font-bold rounded-xl text-xs transition">
                <i class="fa-solid fa-arrow-left mr-1"></i> Kembali
            </a>
        </div>

        <!-- FORM CARD -->
        <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-6">
            <form action="{{ route('employees.store') }}" method="POST" class="space-y-5">
                @csrf

                <!-- INFORMASI KREDENSIAL LOGIN -->
                <div class="p-4 bg-orange-50/50 rounded-2xl border border-orange-100 space-y-3">
                    <h3 class="text-xs font-bold text-[#FF6B00] uppercase tracking-wider flex items-center gap-1.5">
                        <i class="fa-solid fa-key"></i> Kredensial Akses Login Karyawan
                    </h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-bold text-slate-600 uppercase tracking-wider mb-1">
                                Email Login <span class="text-rose-500">*</span>
                            </label>
                            <input type="email" name="email" value="{{ old('email') }}" required
                                placeholder="karyawan@gbparking.com"
                                class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs focus:outline-none focus:border-[#FF6B00] bg-white @error('email') border-red-500 @enderror">
                            @error('email')
                                <p class="text-red-500 text-[10px] mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-600 uppercase tracking-wider mb-1">
                                Password Default <span class="text-rose-500">*</span>
                            </label>
                            <input type="text" name="password" value="{{ old('password', 'gbparking123') }}" required
                                placeholder="Minimal 6 Karakter"
                                class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs focus:outline-none focus:border-[#FF6B00] bg-white @error('password') border-red-500 @enderror">
                            @error('password')
                                <p class="text-red-500 text-[10px] mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- INFORMASI BIODATA -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-600 uppercase tracking-wider mb-1">NIK (Nomor
                            Induk Karyawan) <span class="text-rose-500">*</span></label>
                        <input type="text" name="nik" value="{{ old('nik') }}" required
                            placeholder="Contoh: GB-2024001"
                            class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs focus:outline-none focus:border-[#FF6B00] @error('nik') border-red-500 @enderror">
                        @error('nik')
                            <p class="text-red-500 text-[10px] mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-600 uppercase tracking-wider mb-1">Nama Lengkap
                            <span class="text-rose-500">*</span></label>
                        <input type="text" name="name" value="{{ old('name') }}" required
                            placeholder="Masukkan nama karyawan"
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
                        <input type="text" name="phone" value="{{ old('phone') }}" placeholder="08123456789"
                            class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs focus:outline-none focus:border-[#FF6B00]">
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-600 uppercase tracking-wider mb-1">Status
                            Karyawan</label>
                        <select name="status"
                            class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs focus:outline-none focus:border-[#FF6B00]">
                            <option value="Aktif" {{ old('status') == 'Aktif' ? 'selected' : '' }}>Aktif</option>
                            <option value="Nonaktif" {{ old('status') == 'Nonaktif' ? 'selected' : '' }}>Nonaktif
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
                                    {{ old('department_id') == $department->id ? 'selected' : '' }}>
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
                                    {{ old('location_id') == $location->id ? 'selected' : '' }}>
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
                                    {{ old('position_id') == $position->id ? 'selected' : '' }}>
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
                        <label class="block text-xs font-bold text-slate-600 uppercase tracking-wider mb-1">
                            Gaji Pokok / Rate Harian (Rp) <span class="text-rose-500">*</span>
                        </label>
                        <input type="number" name="basic_salary" value="{{ old('basic_salary') }}" required
                            placeholder="3000000"
                            class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs focus:outline-none focus:border-[#FF6B00]">
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-600 uppercase tracking-wider mb-1">
                            Tipe Karyawan <span class="text-rose-500">*</span>
                        </label>
                        <select name="employee_type" id="employee_type" required
                            class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs focus:outline-none focus:border-[#FF6B00] @error('employee_type') border-red-500 @enderror">
                            <option value="">-- Pilih Tipe Karyawan --</option>
                            <option value="Tetap" {{ old('employee_type') == 'Tetap' ? 'selected' : '' }}>Tetap
                            </option>
                            <option value="Kontrak" {{ old('employee_type') == 'Kontrak' ? 'selected' : '' }}>Kontrak
                            </option>
                            <option value="Harian" {{ old('employee_type') == 'Harian' ? 'selected' : '' }}>Harian
                            </option>
                        </select>
                        @error('employee_type')
                            <p class="text-red-500 text-[10px] mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- TANGGAL BERAKHIR KONTRAK (DYNAMIC SHOW/HIDE) -->
                <div id="contract_date_container" class="{{ old('employee_type') == 'Kontrak' ? '' : 'hidden' }}">
                    <label class="block text-xs font-bold text-slate-600 uppercase tracking-wider mb-1">
                        Tanggal Berakhir Kontrak <span class="text-rose-500">*</span>
                    </label>
                    <input type="date" name="contract_end_date" value="{{ old('contract_end_date') }}"
                        class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs focus:outline-none focus:border-[#FF6B00] @error('contract_end_date') border-red-500 @enderror">
                    @error('contract_end_date')
                        <p class="text-red-500 text-[10px] mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="pt-4 flex justify-end space-x-3 border-t border-slate-100 mt-6">
                    <a href="{{ route('employees.index') }}"
                        class="px-5 py-2.5 border border-slate-200 text-slate-600 font-bold text-xs rounded-xl hover:bg-slate-50 transition">
                        Batal
                    </a>
                    <button type="submit"
                        class="px-5 py-2.5 bg-[#FF6B00] hover:bg-[#e66000] text-white font-bold text-xs rounded-xl shadow-lg shadow-orange-500/20 transition">
                        Simpan & Buat Akun Karyawan
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
