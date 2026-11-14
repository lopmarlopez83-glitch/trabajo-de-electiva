<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('equipos', function (Blueprint $table) {
            $table->id();
            $table->string('serial', 100)->unique();
            $table->string('codigo_patrimonial', 100)->unique()->nullable();
            $table->foreignId('modelo_id')->constrained('modelos')->cascadeOnUpdate()->restrictOnDelete();
            $table->enum('status', ['disponible', 'en_mantenimiento', 'prestado', 'asignado'])->default('disponible');
            $table->text('observaciones')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('equipos');
    }
};