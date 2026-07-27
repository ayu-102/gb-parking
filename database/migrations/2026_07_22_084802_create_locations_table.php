<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('locations', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // Nama Cabang / Lokasi GB Parking
            $table->text('address')->nullable();
            $table->string('city')->nullable();
            $table->integer('radius')->default(100); // Radius Absensi (meter)
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('locations');
    }
};
