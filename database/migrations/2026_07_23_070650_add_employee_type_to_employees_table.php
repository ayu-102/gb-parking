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
            // Pilihan: Tetap atau Kontrak (default Kontrak)
            $table->enum('employee_type', ['Tetap', 'Kontrak'])->default('Kontrak')->after('status');
        });
    }

    public function down(): void
    {
        Schema::table('employees', function (Blueprint $table) {
            $table->dropColumn('employee_type');
        });
    }
};
