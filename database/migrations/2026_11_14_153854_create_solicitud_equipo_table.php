<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('solicitud_equipo', function (Blueprint $table) {
            $table->id();
            $table->foreignId('solicitud_id')->constrained('solicitudes')->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreignId('equipo_id')->constrained('equipos')->cascadeOnUpdate()->restrictOnDelete();
            $table->timestamps();

            $table->unique(['solicitud_id', 'equipo_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('solicitud_equipo');
    }
};