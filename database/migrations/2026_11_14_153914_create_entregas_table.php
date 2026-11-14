<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('entregas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('solicitud_id')->constrained('solicitudes')->cascadeOnUpdate()->restrictOnDelete();
            $table->foreignId('equipo_id')->constrained('equipos')->cascadeOnUpdate()->restrictOnDelete();
            $table->foreignId('user_id')->constrained('users')->cascadeOnUpdate()->restrictOnDelete(); // quien recibe
            $table->foreignId('oficina_id')->constrained('oficinas')->cascadeOnUpdate()->restrictOnDelete();
            $table->enum('tipo', ['prestamo', 'asignacion']);
            $table->dateTime('fecha_entrega');
            $table->dateTime('fecha_devolucion_esperada')->nullable();
            $table->dateTime('fecha_devolucion_real')->nullable();
            $table->enum('status', ['activa', 'devuelta'])->default('activa');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('entregas');
    }
};