<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('presences', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employee_id')->constrained()->cascadeOnDelete();
            $table->date('date');

            // Jam & Data Masuk
            $table->time('time_in')->nullable();
            $table->string('photo_in')->nullable();
            $table->string('lat_in')->nullable();
            $table->string('long_in')->nullable();

            // Jam & Data Pulang
            $table->time('time_out')->nullable();
            $table->string('photo_out')->nullable();
            $table->string('lat_out')->nullable();
            $table->string('long_out')->nullable();

            // Status: Hadir, Terlambat, Pulang Cepat
            $table->string('status')->default('Hadir');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('presences');
    }
};
