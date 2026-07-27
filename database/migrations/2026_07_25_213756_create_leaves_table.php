<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('leaves', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employee_id')->constrained()->cascadeOnDelete();
            $table->enum('type', ['Cuti', 'Izin', 'Sakit'])->default('Cuti');
            $table->date('start_date');
            $table->date('end_date');
            $table->integer('total_days')->default(1);
            $table->text('reason');
            $table->string('attachment')->nullable(); // Foto/Surat Dokter jika ada
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->text('rejection_note')->nullable(); // Alasan jika ditolak HRD
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('leaves');
    }
};
