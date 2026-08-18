<x-app-layout>
    <div class="max-w-xl mx-auto space-y-6">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-xl font-bold text-slate-800">Hitung Draft Payroll</h1>
                <p class="text-xs text-slate-500 mt-1">Pilih tipe penggajian, karyawan, dan periode/tanggal.</p>
            </div>
            <a href="{{ route('payrolls.index') }}"
                class="px-3.5 py-2 bg-slate-100 text-slate-600 font-bold rounded-xl text-xs hover:bg-slate-200 transition">
                <i class="fa-solid fa-arrow-left mr-1"></i> Kembali
            </a>
        </div>

        <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-6">
            <form action="{{ route('payrolls.store') }}" method="POST" class="space-y-4">
                @csrf

                <!-- TIPE PENGGAJIAN -->
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">
                        Tipe Penggajian
                    </label>
                    <select name="payroll_type" id="payroll_type" required
                        class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs font-bold focus:outline-none focus:border-[#FF6B00]">
                        <option value="Bulanan">Payroll Bulanan (Gaji Bulanan / Tetap / Kontrak)</option>
                        <option value="Harian">Payroll Harian / Event (Gaji Harian)</option>
                    </select>
                </div>

                <!-- PILIH KARYAWAN -->
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">
                        Pilih Karyawan
                    </label>
                    <select name="employee_id" id="employee_id" required
                        class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs focus:outline-none focus:border-[#FF6B00]">
                        <option value="">-- Pilih Karyawan --</option>
                        @foreach ($employees as $emp)
                            <option value="{{ $emp->id }}" data-type="{{ $emp->employee_type }}">
                                {{ $emp->name }} ({{ $emp->nik ?? '-' }}) - Tipe:
                                {{ $emp->employee_type ?? 'Tetap' }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- CONTAINER PERIODE BULANAN -->
                <div id="container_bulanan">
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">
                        Periode Bulan & Tahun
                    </label>
                    <input type="month" name="month_year" id="input_month_year" value="{{ date('Y-m') }}"
                        class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs focus:outline-none focus:border-[#FF6B00]">
                </div>

                <!-- CONTAINER TANGGAL HARIAN -->
                <div id="container_harian" class="hidden">
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">
                        Tanggal Penggajian Harian
                    </label>
                    <input type="date" name="payroll_date" id="input_payroll_date" value="{{ date('Y-m-d') }}"
                        class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs focus:outline-none focus:border-[#FF6B00]">
                </div>

                <!-- CATATAN -->
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">
                        Catatan Tambahan (Opsional)
                    </label>
                    <textarea name="notes" rows="3" placeholder="Catatan khusus hitungan gaji ini..."
                        class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs focus:outline-none focus:border-[#FF6B00]"></textarea>
                </div>

                <div class="pt-4 flex justify-end space-x-3">
                    <a href="{{ route('payrolls.index') }}"
                        class="px-4 py-2.5 bg-slate-100 text-slate-600 font-bold rounded-xl text-xs">Batal</a>
                    <button type="submit"
                        class="px-5 py-2.5 bg-[#FF6B00] hover:bg-[#e66000] text-white font-bold rounded-xl text-xs shadow-lg shadow-orange-500/20 transition">
                        Proses & Hitung
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const payrollTypeSelect = document.getElementById('payroll_type');
            const employeeSelect = document.getElementById('employee_id');
            const options = Array.from(employeeSelect.options);

            const containerBulanan = document.getElementById('container_bulanan');
            const containerHarian = document.getElementById('container_harian');
            const inputMonthYear = document.getElementById('input_month_year');
            const inputPayrollDate = document.getElementById('input_payroll_date');

            function syncForm() {
                const selectedType = payrollTypeSelect.value;

                if (selectedType === 'Harian') {
                    containerBulanan.classList.add('hidden');
                    inputMonthYear.disabled = true;

                    containerHarian.classList.remove('hidden');
                    inputPayrollDate.disabled = false;
                } else {
                    containerBulanan.classList.remove('hidden');
                    inputMonthYear.disabled = false;

                    containerHarian.classList.add('hidden');
                    inputPayrollDate.disabled = true;
                }

                // Filter dropdown karyawan sesuai tipe
                options.forEach(option => {
                    if (!option.value) return;
                    const empType = option.getAttribute('data-type');
                    if (selectedType === 'Harian') {
                        option.hidden = (empType !== 'Harian');
                    } else {
                        option.hidden = (empType === 'Harian');
                    }
                });
            }

            // Otomatis pindahkan Tipe Penggajian sesuai karyawan yang dipilih
            employeeSelect.addEventListener('change', function() {
                const selectedOption = this.options[this.selectedIndex];
                const empType = selectedOption.getAttribute('data-type');

                if (empType === 'Harian') {
                    payrollTypeSelect.value = 'Harian';
                } else if (empType === 'Kontrak' || empType === 'Tetap') {
                    payrollTypeSelect.value = 'Bulanan';
                }
                syncForm();
            });

            payrollTypeSelect.addEventListener('change', syncForm);
            syncForm();
        });
    </script>
</x-app-layout>
