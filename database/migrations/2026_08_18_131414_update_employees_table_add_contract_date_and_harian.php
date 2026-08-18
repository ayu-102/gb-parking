<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('employees', function (Blueprint $table) {
    // Mengubah ENUM agar mendukung 'Harian'
    $table->enum('employee_type', ['Tetap', 'Kontrak', 'Harian'])->default('Kontrak')->change();
    // Menambah tanggal berakhir kontrak (nullable untuk karyawan Tetap/Harian)
    $table->date('contract_end_date')->nullable()->after('employee_type');
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
