<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('solicitudes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnUpdate()->restrictOnDelete();
            $table->foreignId('oficina_id')->constrained('oficinas')->cascadeOnUpdate()->restrictOnDelete();
            $table->date('fecha_solicitud');
            $table->date('fecha_requerida');
            $table->date('fecha_fin')->nullable();
            $table->unsignedInteger('cantidad');
            $table->enum('tipo', ['prestamo', 'asignacion']);
            $table->enum('status', ['pendiente', 'aprobada', 'rechazada'])->default('pendiente');
            $table->text('motivo_rechazo')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('solicitudes');
    }
};