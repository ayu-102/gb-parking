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
        Schema::create('payrolls', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employee_id')->constrained()->cascadeOnDelete();
            $table->string('month_year'); // Format: YYYY-MM (misal: 2026-07)
            $table->decimal('basic_salary', 15, 2)->default(0);
            $table->decimal('total_allowance', 15, 2)->default(0); // Tunjangan
            $table->decimal('total_bonus', 15, 2)->default(0);     // Bonus & Insentif
            $table->decimal('total_deduction', 15, 2)->default(0); // Potongan
            $table->decimal('net_salary', 15, 2)->default(0);     // Gaji Bersih (Take Home Pay)
            $table->enum('status', ['draft', 'pending', 'approved', 'rejected'])->default('draft');
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payrolls');
    }
};
