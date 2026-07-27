<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('shift_templates', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // Pagi, Siang, Malam, Off
            $table->time('start_time');
            $table->time('end_time');
            $table->decimal('duration_hours', 4, 2)->default(8);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('shift_templates');
    }
};
