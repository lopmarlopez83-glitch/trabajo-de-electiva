<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('modelos', function (Blueprint $table) {
            $table->id();
            $table->string('nombre', 150);
            $table->foreignId('marca_id')->constrained('marcas')->cascadeOnUpdate()->restrictOnDelete();
            $table->foreignId('categoria_id')->constrained('categorias')->cascadeOnUpdate()->restrictOnDelete();
            $table->timestamps();
            $table->softDeletes();

            $table->unique(['nombre', 'marca_id', 'categoria_id'], 'modelo_marca_categoria_unique');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('modelos');
    }
};