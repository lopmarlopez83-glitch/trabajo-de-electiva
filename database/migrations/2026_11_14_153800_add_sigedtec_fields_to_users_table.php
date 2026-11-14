<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('nombres', 100)->after('id');
            $table->string('apellidos', 100)->after('nombres');
            $table->string('cedula', 20)->unique()->after('apellidos');
            $table->foreignId('oficina_id')->after('email')->constrained('oficinas')->cascadeOnUpdate()->restrictOnDelete();
            $table->enum('role', ['admin', 'solicitante'])->default('solicitante')->after('oficina_id');
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['oficina_id']);
            $table->dropColumn(['nombres', 'apellidos', 'cedula', 'oficina_id', 'role', 'deleted_at']);
        });
    }
};