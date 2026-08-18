<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('employees', function (Blueprint $table) {
            // Kolom penanda jenis karyawan (Tetap, Kontrak, Harian)
            if (!Schema::hasColumn('employees', 'employee_type')) {
                $table->enum('employee_type', ['Tetap', 'Kontrak', 'Harian'])->default('Tetap')->after('basic_salary');
            }
            // Tanggal berakhirnya kontrak (khusus Kontrak)
            if (!Schema::hasColumn('employees', 'contract_end_date')) {
                $table->date('contract_end_date')->nullable()->after('employee_type');
            }
            // Upah harian / per shift (khusus Harian)
            if (!Schema::hasColumn('employees', 'daily_rate')) {
                $table->decimal('daily_rate', 15, 2)->default(0)->after('contract_end_date');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('employees', function (Blueprint $table) {
            //
        });
    }
};
